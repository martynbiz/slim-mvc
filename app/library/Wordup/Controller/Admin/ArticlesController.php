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
        $articles = $this->get('model.article')->findArticlesManagedBy($currentUser);

        return $this->render('admin.articles.index', compact('articles'));
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

        return $this->render('admin.articles.show', compact('article'));
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

        return $this->render('admin.articles.edit', compact('article', 'tags'));
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
        $this->attachPhotosTo($article, @$_FILES['photos']);

        if ( $article->save($params) ) {
            $this->get('flash')->addMessage('success', 'Draft article saved. Click "submit" when ready to publish.');
            return $this->redirect('/admin/articles/' . $id . '/edit');
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('edit', compact('id'));
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
        $this->attachPhotosTo($article, @$_FILES['photos']);

        // set tags from the params tags value submitted
        // this will also ensure than only valid tags are used
        $params['tags'] = $this->getTagsFromTagIds(@$params['tags']);

        if ( $article->save( $this->getPost() ) ) {
            $this->get('flash')->addMessage('success', 'Article has been submitted and will be reviewed by an editor shortly.');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('edit', compact('id'));
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
        $this->attachPhotosTo($article, @$_FILES['photos']);

        if ( $article->save( $this->getPost() ) ) {
            $this->get('flash')->addMessage('success', 'Article has been approved.');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('edit', compact('id'));
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
    protected function attachPhotosTo(Mongo $target, $photos)
    {
        $container = $this->app->getContainer();
        $settings = $container->get('settings');

        // generate the photo dir from the target id
        // we'll use Photo::getCurrentDir to generate the dir from date
        // useful when managing thousands of photos/articles
        // e.g. /var/www/.../data/photos/201601/31/
        $dir = $settings['photos_dir']['original'] . '/' . Photo::getCurrentDir();
        if (!file_exists($dir) and !mkdir($dir, 0775, true)) {
            throw new \Exception('Could not create directory');
        }

        // loop through photos and create in photos collection
        // also, attach the newly created photo to article
        foreach(@$photos['name'] as $i => $file) {

            $name = $photos['name'][$i];
            $tmpName = $photos['tmp_name'][$i];
            $type = $photos['type'][$i];
            $ext = pathinfo($name, PATHINFO_EXTENSION);

            // if the file field is blank, move onto the next field
            if (empty($file)) continue;

            // get the dimensions so we can calculate the width/height ratio
            // throw an exception if this fails
            list($width_orig, $height_orig) = getimagesize($tmpName);
            if (!$width_orig or !$height_orig)
                throw new \Exception('Could not get image size from uploaded image.');

            // calculate new image size with ratio if exceeds max
            // TODO put this into Photo as static, unit test
            $ratio_orig = $width_orig/$height_orig;

            // Set a maximum height and width
            $width = 2000;
            $height = 2000;
            if ($width/$height > $ratio_orig) {
               $width = ceil($height*$ratio_orig);
            } else {
               $height = ceil($width/$ratio_orig);
            }

            // Create a new image from the uploaded file
            $src = imagecreatefromjpeg($tmpName);
            if (!$src)
                throw new \Exception('Only JPEG images are allowed for photos.');

            // Create a new true color image and copy and resize part of an image
            // with resampling
            $tmp = imagecreatetruecolor($width, $height);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

            // first, move the file to it's desired location
            // e.g. /var/www/.../data/photos/11/00/17/7sdfdfsfs.jpg
            $file = sprintf('%s.%s', substr(md5_file($tmpName), 0, 10), strtolower($ext));
            $destpath = $dir . '/' . $file;
            // move_uploaded_file($tmpName, $destpath);

            imagejpeg($tmp, $destpath);
            imagedestroy($tmp);

            // create the photo in collection first so that we have an id to
            // name the photo by
            $photo = $this->get('model.photo')->create(array(
                'original_file' => $file,
                'type' => $type,
                'width' => $width,
                'height' => $height,
            ));

            // attach the photo to $article
            $target->push( array(
                'photos' => $photo,
            ) );

        }
    }
}
