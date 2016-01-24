<?php
namespace Tests\Controllers\Admin;

// use Wordup\Model\Article;

class TagsControllerTest extends \TestCase
{

    // ======================================
    // test routes when not authenticated redirect to login page

    public function testIndexActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/tags');
        $this->assertRedirectsTo('/session/login');
    }

    public function testEditActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/tags/1/edit');
        $this->assertRedirectsTo('/session/login');
    }

    public function testUpdateActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->put('/admin/tags/1');
        $this->assertRedirectsTo('/session/login');
    }

    public function testDeleteActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->delete('/admin/tags/1');
        $this->assertRedirectsTo('/session/login');
    }


    // ======================================
    // test routes when authenticated

    public function testIndexActionWhenLoggedInAsAdminUserShowsUsers()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->get('/admin/tags');

        // assertions
        $this->assertController('tags');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testIndexActionWhenLoggedInAsEditorUserShowsUser()
    {
        $this->login($this->editorUser);

        // dispatch
        $this->get('/admin/tags');

        // assertions
        $this->assertController('tags');
        $this->assertAction('index');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testIndexActionWhenLoggedInAsOwnerUserShowsUser()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/tags');

        // assertions
        $this->assertController('tags');
        $this->assertAction('index');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testIndexActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/tags');

        // assertions
        $this->assertController('tags');
        $this->assertAction('edit');
    }


    // create

    public function testCreateActionWhenLoggedInAsAdminUserReturns200()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->get('/admin/tags/create');

        // assertions
        $this->assertController('tags');
        $this->assertAction('create');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testCreateActionWhenLoggedInAsEditorRedirectsToLogin()
    {
        $this->login($this->editorUser);

        // dispatch
        $this->get('/admin/tags/create');

        // assertions
        $this->assertController('tags');
        $this->assertAction('create');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testCreateActionWhenLoggedInAsMemberRedirectsToLogin()
    {
        $this->login($this->ownerUser);

        // dispatch
        $this->get('/admin/tags/create');

        // assertions
        $this->assertController('tags');
        $this->assertAction('create');
        $this->assertStatusCode(200);
    }


    // post

    public function testPostActionRedirectsToIndexWhenLoggedInAsAdmin()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->post('/admin/tags', $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('post');
        $this->assertRedirectsTo('/admin/tags');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testPostActionRedirectsToLoginWhenLoggedInAsEditor()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->post('/admin/tags', $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('post');
        $this->assertRedirectsTo('/admin/tags');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testPostActionRedirectsToLoginWhenLoggedInAsMember()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->post('/admin/tags', $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('post');
        $this->assertRedirectsTo('/admin/tags');
    }


    // edit

    public function testEditActionWhenLoggedInAsAdminUserShowsUser()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->get('/admin/tags/' . $this->tag->id . '/edit');

        // assertions
        $this->assertController('tags');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testEditActionWhenLoggedInAsEditorUserShowsUser()
    {
        $this->login($this->editorUser);

        // dispatch
        $this->get('/admin/tags/' . $this->tag->id . '/edit');

        // assertions
        $this->assertController('tags');
        $this->assertAction('edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testEditActionWhenLoggedInAsOwnerUserShowsUser()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/tags/' . $this->tag->id . '/edit');

        // assertions
        $this->assertController('tags');
        $this->assertAction('edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testEditActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->get('/admin/tags/' . $this->tag->id . '/edit');

        // assertions
        $this->assertController('tags');
        $this->assertAction('edit');
    }


    // update

    public function testUpdateActionRedirectsToEditWhenLoggedInAsAdmin()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/tags/' . $this->tag->id, $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/tags');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsMember()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/tags/' . $this->tag->id, $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/tags/' . $this->tag->id . '/edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsOwner()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/tags/' . $this->tag->id, $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/tags/' . $this->tag->id . '/edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsRandom()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/tags/' . $this->tag->id, $this->getTagData());

        // assertions
        $this->assertController('tags');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/tags/' . $this->tag->id . '/edit');
    }


    // delete

    public function testDeleteActionWhenLoggedInAsAdminUserShowsUser()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/tags/' . $this->tag->id);

        // assertions
        $this->assertController('tags');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/tags');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsEditorUserShowsUser()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/tags/' . $this->tag->id);

        // assertions
        $this->assertController('tags');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/tags');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsOwnerUserShowsUser()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/tags/' . $this->tag->id);

        // assertions
        $this->assertController('tags');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/tags');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/tags/' . $this->tag->id);

        // assertions
        $this->assertController('tags');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/tags');
    }


    // data providers

    public function getTagData($data=array())
    {
        return array_merge( array(
            'name' => 'Japanese',
            'slug' => 'japanese',
        ), $data );
    }
}
