<?php
namespace Tests\Controllers\Admin;

use Wordup\Model\Article;

class ArticlesControllerTest extends \TestCase
{

    // ======================================
    // test routes when not authenticated redirect to login page

    public function testIndexActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/articles');
        $this->assertRedirectsTo('/session/login');
    }

    public function testShowActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/articles/1');
        $this->assertRedirectsTo('/session/login');
    }

    public function testPostActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->post('/admin/articles');
        $this->assertRedirectsTo('/session/login');
    }

    public function testEditActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/articles/1/edit');
        $this->assertRedirectsTo('/session/login');
    }

    public function testUpdateActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->put('/admin/articles/1');
        $this->assertRedirectsTo('/session/login');
    }

    public function testSubmitActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->put('/admin/articles/1/submit');
        $this->assertRedirectsTo('/session/login');
    }

    public function testApproveActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->put('/admin/articles/1/approve');
        $this->assertRedirectsTo('/session/login');
    }

    public function testDeleteActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->delete('/admin/articles/1');
        $this->assertRedirectsTo('/session/login');
    }


    // ======================================
    // test routes when authenticated

    public function testIndexActionWhenAuthenticatedReturns200()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles');

        // assertions
        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }


    // test permissions (e.g. can view article)

    // show

    public function testShowActionWhenLoggedInAsAdminUserShowsArticle()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }

    public function testShowActionWhenLoggedInAsEditorUserShowsArticle()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }

    public function testShowActionWhenLoggedInAsOwnerUserShowsArticle()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testShowActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }


    // edit

    public function testEditActionWhenLoggedInAsAdminUserShowsArticle()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id . '/edit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    public function testEditActionWhenLoggedInAsEditorUserShowsArticle()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id . '/edit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    public function testEditActionWhenLoggedInAsOwnerUserShowsArticle()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id . '/edit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testEditActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/articles/' . $this->article->id . '/edit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }


    // update

    public function testUpdateActionRedirectsToEditWhenLoggedInAsAdmin()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id, $this->getArticleData());

        // assertions
        $this->assertController('articles');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id . '/edit');
    }

    public function testUpdateActionRedirectsToEditWhenLoggedInAsMember()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id, $this->getArticleData());

        // assertions
        $this->assertController('articles');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id . '/edit');
    }

    public function testUpdateActionRedirectsToEditWhenLoggedInAsOwner()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id, $this->getArticleData());

        // assertions
        $this->assertController('articles');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id . '/edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsRandom()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id, $this->getArticleData());

        // assertions
        $this->assertController('articles');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id . '/edit');
    }


    // delete

    public function testDeleteActionWhenLoggedInAsAdminUserShowsArticle()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/articles');
    }

    public function testDeleteActionWhenLoggedInAsEditorUserShowsArticle()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/articles');
    }

    public function testDeleteActionWhenLoggedInAsOwnerUserShowsArticle()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/articles');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/articles/' . $this->article->id);

        // assertions
        $this->assertController('articles');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/articles');
    }


    // submit

    public function testSubmitActionWhenLoggedInAsAdminUserShowsArticle()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/submit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('submit');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }

    public function testSubmitActionWhenLoggedInAsEditorUserShowsArticle()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/submit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('submit');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }

    public function testSubmitActionWhenLoggedInAsOwnerUserShowsArticle()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/submit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('submit');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testSubmitActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/submit');

        // assertions
        $this->assertController('articles');
        $this->assertAction('submit');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }


    // approve

    public function testApproveActionWhenLoggedInAsAdminUserShowsArticle()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/approve');

        // assertions
        $this->assertController('articles');
        $this->assertAction('approve');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }

    public function testApproveActionWhenLoggedInAsEditorUserShowsArticle()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/approve');

        // assertions
        $this->assertController('articles');
        $this->assertAction('approve');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }

    public function testApproveActionWhenLoggedInAsOwnerUserShowsArticle()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/approve');

        // assertions
        $this->assertController('articles');
        $this->assertAction('approve');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testApproveActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/articles/' . $this->article->id . '/approve');

        // assertions
        $this->assertController('articles');
        $this->assertAction('approve');
        $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    }


    // post

    public function testPostCreateArticleRedirects()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->post('/admin/articles?type=' . Article::TYPE_ARTICLE);

        // assertions
        $this->assertController('articles');
        $this->assertAction('post');
        $this->assertRedirects(); // difficult to obtain new article id for url
    }


    // data providers

    public function getArticleData($data=array())
    {
        return array_merge( array(
            'title' => 'A long time ago in a galaxy far far away',
            'description' => '<p>Hello world!</p>',
        ), $data );
    }
}
