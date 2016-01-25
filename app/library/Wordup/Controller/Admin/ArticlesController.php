<?php
namespace Wordup\Controller\Admin;

use MartynBiz\Mongo;
use Wordup\Controller\BaseController;
use Wordup\Model\Article;
use Wordup\Model\Photo;
use Wordup\Exception\PermissionDenied;

class ArticlesController extends BaseController
{
    public function index()
    {
        $currentUser = $this->get('auth')->getCurrentUser();

        // for now, members can view only thiers and admin/editors can view all
        // TODO perhaps have a ("model.article")->findArticlesManagedBy($currentUser)
        if ( $currentUser->isMember() or true) {
            $articles = $this->get('model.article')->findArticlesOf($currentUser);
        } else {
            $articles = $this->get('model.article')->find(); // TODO paginate
        }

        // $articles = $this->get('model.article')->find();

        return $this->render('admin/articles/index.html', array(
            'articles' => $articles->toArray(),
        ));
    }

    /**
     * This view will serve as a review page where the author can re-open for
     * further editing, or an admin/editor can approve to go live.
     */
    public function show($id)
    {
        $currentUser = $this->get('auth')->getCurrentUser();

        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // ensure that this user can edit this article
        if (! $currentUser->canView($article) ) {
            throw new PermissionDenied('Permission denied to view this article.');
        }

        return $this->render('admin/articles/show.html', array(
            'article' => $article->toArray(),
        ));
    }

    /**
     * This works where by a user will select to create an article, and instantly
     * a new draft article is created. This way we can auto save the article before
     * they submit it. or they can keep it as a draft. Upon immediate creation, it
     * should redirect to the edit article form (although it will intially be empty)
     */
    public function post()
    {
        $currentUser = $this->get('auth')->getCurrentUser();
        $article = $this->get('model.article')->factory();

        // for security reasons, some properties are not on the whitelist but
        // we can directly assign
        $article->status = Article::STATUS_DRAFT;
        $article->type = Article::TYPE_ARTICLE;
        $article->author = $currentUser->getDBRef();

        // if the article saves ok, redirect them to the edit page where they can
        // begin to edit their draft. any errors, forward them back to the index
        if ( $article->save() ) {
            return $this->redirect('/admin/articles/' . $article->id . '/edit');
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('index');
        }
    }

    /**
     * Upon creation too, the user will be redirect here to edit the article
     */
    public function edit($id)
    {
        $currentUser = $this->get('auth')->getCurrentUser();

        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // ensure that this user can edit this article
        if (! $currentUser->canEdit($article) ) {
            throw new PermissionDenied('Permission denied to edit this article.');
        }

        // get tags from cache
        $cacheId = 'tags';
        if (! $tags = $this->get('cache')->get($cacheId)) {
            $tags = $this->get('model.tag')->find();
            $this->get('cache')->set($cacheId, $tags, 1);
        }

        return $this->render('admin/articles/edit.html', array(
            'article' => array_merge($article->toArray(), $this->getPost()),
            'tags' => $tags->toArray(),
        ));
    }

    /**
     * This method will update the article (save draft) and 1) if xhr, return json,
     * or 2) redirect back to the edit page (upon which they can then submit when they
     * choose to)
     */
    public function update($id)
    {
        $currentUser = $this->get('auth')->getCurrentUser();
        $params = $this->getPost();
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // ensure that this user can edit this article
        if (! $currentUser->canEdit($article) ) {
            throw new PermissionDenied('Permission denied to edit this article.');
        }

        // set tags from the params tags value submitted
        // this will also ensure than only valid tags are used
        $params['tags'] = $this->getTagsFromTagIds(@$params['tags']);

        // handle photos
        $this->attachPhotosTo($article);

        if ( $article->save($params) ) {
            $this->get('flash')->addMessage('success', 'Draft article saved. Click "submit" when ready to publish.');
            return $this->redirect('/admin/articles/' . $id . '/edit');
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('edit', array(
                'id' => $id,
            ));
        }
    }

    /**
     * This method is used when the contributer has finished editing and ready to
     * publish. They will be redirected to the "show" page from there they can
     * review and open up for additional changes. from there, an admin user can also
     * approve the article to make it live.
     */
    public function submit($id)
    {
        $currentUser = $this->get('auth')->getCurrentUser();
        $params = $this->getPost();
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // only top brass can approve
        if (! $currentUser->canSubmit($article) ) {
            throw new PermissionDenied('Permission denied to submit this article.');
        }

        // set the status of the article to approved, if there are any problems
        // with the data send, save() will fail anyway. Using set() here as it is
        // more testable as a method :)
        $article->set('status', Article::STATUS_SUBMITTED);

        // handle photos
        $this->attachPhotosTo($article, @$params['photos']);

        // set tags from the params tags value submitted
        // this will also ensure than only valid tags are used
        $params['tags'] = $this->getTagsFromTagIds(@$params['tags']);

        if ( $article->save( $this->getPost() ) ) {
            $this->get('flash')->addMessage('success', 'Article has been submitted and will be reviewed by an editor shortly.');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('edit', array(
                'id' => $id,
            ));
        }
    }

    /**
     * Only editor and admin users can approve articles
     */
    public function approve($id)
    {
        $currentUser = $this->get('auth')->getCurrentUser();
        $params = $this->getPost();
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // only top brass can approve
        if (! $currentUser->canApprove($article) ) {
            throw new PermissionDenied('Permission denied to approve this article.');
        }

        // set the status of the article to approved, if there are any problems
        // with the data send, save() will fail anyway. Using set() here as it is
        // more testable as a method :)
        $article->set('status', Article::STATUS_APPROVED);

        // set tags from the params tags value submitted
        // this will also ensure than only valid tags are used
        $params['tags'] = $this->getTagsFromTagIds(@$params['tags']);

        // handle photos
        $this->attachPhotosTo($article, @$params['photos']);

        if ( $article->save( $this->getPost() ) ) {
            $this->get('flash')->addMessage('success', 'Article has been approved.');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('edit', array(
                'id' => $id,
            ));
        }
    }

    public function delete($id)
    {
        $currentUser = $this->get('auth')->getCurrentUser();

        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // only top brass can delete
        if (! $currentUser->canDelete($article) ) {
            throw new PermissionDenied('Permission denied to delete this article.');
        }

        if ( $article->delete() ) {
            $this->get('flash')->addMessage('success', 'Article deleted successfully');
            return $this->redirect('/admin/articles');
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->edit($id);
        }
    }

    /**
     * Fetch tags from the tag $ids
     * @param int|array $ids Array (or single int) of tag ids
     * @return MartynBiz\Mongo\MongoIterator
     */
    protected function getTagsFromTagIds($ids)
    {
        // if not an array (e.g. single id), set as one
        // makes life easier for the query when use of "$in" can be assumed
        if (! is_array($ids)) $ids = array($ids);

        // ids need to be integers, otherwise they won't fetch anything
        foreach ($ids as $i => $id) {
            $ids[$i] = (int) $id;
        }

        // get tags from tags[] and write back to params['tags']
        return $this->get('model.tag')->find(array(
            'id' => array(
                '$in' => $ids,
            ),
        ));
    }

    /**
     * Create photos in photos collection and attach to Mongo object (e.g. Article)
     * @param Mongo $article Article to attach the photos to
     * @param array $photos POST param
     * @return void
     */
    protected function attachPhotosTo(Mongo $target)
    {
        // $container = $this->app->getContainer();
        // $settings = $container->get('settings');
        //
        // // generate the photo dir from the target id
        // // we'll use Photo::getDir to generate the dir hash which will be
        // // useful when managing thousands of photos/articles
        // // e.g. /var/www/.../data/photos/11/00/17/
        // $destpath = $settings['photos_dir'] . Photo::getDir($target->id);
        // if (!file_exists($destpath) and !mkdir($destpath, 0775, true)) {
        //     throw new \Exception('Could not create directory');
        // }
        //
        // // loop through photos and create in photos collection
        // // also, attach the newly created photo to article
        // foreach(@$_FILES['photos']['name'] as $i => $file) {
        //
        //     $name = $_FILES['photos']['name'][$i];
        //     $tmpName = $_FILES['photos']['tmp_name'][$i];
        //     $type = $_FILES['photos']['type'][$i];
        //     $ext = pathinfo($name, PATHINFO_EXTENSION);
        //
        //     // TODO validation
        //
        //
        //     // first, move the file to it's desired location
        //     // e.g. /var/www/.../data/photos/11/00/17/7sdfdfsfs.jpg
        //     $destpath = sprintf('%s/%s.%s', $destpath, uniqid(), strtolower($ext));
        //     move_uploaded_file($tmpName, $destpath);
        //
        //     // create the photo in collection first so that we have an id to
        //     // name the photo by
        //     $photo = $this->get('model.photo')->create(array(
        //         'file_path' => $destpath,
        //         'type' => $type,
        //     ));
        //
        //     // attach the photo to $article
        //     // TODO enable Mongo to handle this
        //     $target->push( array(
        //         'photos' => $photo,
        //     ) );
        //
        // }
    }
}
