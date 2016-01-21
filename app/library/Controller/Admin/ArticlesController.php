<?php
namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Model\Article;

class ArticlesController extends BaseController
{
    public function index()
    {
        $articles = $this->get('model.article')->find();

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
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

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
        // TODO change mongo so that we can insert an empty draft
        $article = $this->get('model.article')->factory(array(
            'type' => Article::TYPE_ARTICLE,
        ));

        // for security reasons, status isn't on the whitelist for mass assignment
        // but we can set it via property assignment.
        $article->set('status', Article::STATUS_DRAFT);

        // if the article saves ok, redirect them to the edit page where they can
        // begin to edit their draft. any errors, forward them back to the index
        // (where they came from)
        if ( $article->save() ) {

            // using get('id') here so we can mock $article during testing
            return $this->redirect('/admin/articles/' . $article->get('id') . '/edit');
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
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // // as the article is being edited again, it will need re-approved
        // // if the user is an admin/editor user, set this to SUBMITTED, otherwise
        // // put it back to DRAFT TODO think this out, unit test too
        // if ($article->status == Article::APPROVED) {
        //     $article->status = Article::STATUS_DRAFT;
        //     $article->save();
        // }

        return $this->render('admin/articles/edit.html', array(
            'article' => array_merge($article->toArray(), $this->getPost()),
        ));
    }

    /**
     * This method will update the article (save draft) and 1) if xhr, return json,
     * or 2) redirect back to the edit page (upon which they can then submit when they
     * choose to)
     */
    public function update($id)
    {
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        if ( $article->save( $this->getPost() ) ) {

            if ($this->isXhr()) {

                // render
                return $this->renderJson(array(
                    'article' => $article->toArray(),
                ));
            } else {

                $this->get('flash')->addMessage('success', 'Draft article saved. Click "submit" when ready to publish.');

                // redirect
                return $this->redirect('/admin/articles/' . $id . '/edit');
            }
        } else {

            $this->get('flash')->addMessage('errors', $article->getErrors());

            // forward
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
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // set the status of the article to approved, if there are any problems
        // with the data send, save() will fail anyway. Using set() here as it is
        // more testable as a method :)
        $article->set('status', Article::STATUS_SUBMITTED);

        if ( $article->save( $this->getPost() ) ) {

            $this->get('flash')->addMessage('success', 'Article has been submitted and will be reviewed by an editor shortly.');

            // redirect
            return $this->redirect('/admin/articles/' . $id);

        } else {

            $this->get('flash')->addMessage('errors', $article->getErrors());

            // forward
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
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        // set the status of the article to approved, if there are any problems
        // with the data send, save() will fail anyway. Using set() here as it is
        // more testable as a method :)
        $article->set('status', Article::STATUS_APPROVED);

        if ( $article->save( $this->getPost() ) ) {

            $this->get('flash')->addMessage('success', 'Article has been approved.');

            // redirect
            return $this->redirect('/admin/articles/' . $id);

        } else {

            $this->get('flash')->addMessage('errors', $article->getErrors());

            // forward
            return $this->forward('edit', array(
                'id' => $id,
            ));
        }
    }

    public function delete($id)
    {
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        if ( $article->delete() ) {
            $this->get('flash')->addMessage('success', 'Article deleted successfully');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->edit($id);
        }
    }
}
