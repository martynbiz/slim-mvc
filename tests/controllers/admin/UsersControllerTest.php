<?php
namespace Tests\Controllers\Admin;

use Wordup\Model\Article;

class UsersControllerTest extends \TestCase
{

    // ======================================
    // test routes when not authenticated redirect to login page

    public function testIndexActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/users');
        $this->assertRedirectsTo('/session/login');
    }

    public function testEditActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/users/1/edit');
        $this->assertRedirectsTo('/session/login');
    }

    public function testUpdateActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->put('/admin/users/1');
        $this->assertRedirectsTo('/session/login');
    }

    public function testDeleteActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->delete('/admin/users/1');
        $this->assertRedirectsTo('/session/login');
    }


    // ======================================
    // test routes when authenticated

    public function testIndexActionWhenLoggedInAsAdminUserShowsUsers()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->get('/admin/users');

        // assertions
        $this->assertController('users');
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
        $this->get('/admin/users');

        // assertions
        $this->assertController('users');
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
        $this->get('/admin/users');

        // assertions
        $this->assertController('users');
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
        $this->get('/admin/users');

        // assertions
        $this->assertController('users');
        $this->assertAction('edit');
    }


    // edit

    public function testEditActionWhenLoggedInAsAdminUserShowsUser()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->get('/admin/users/' . $this->ownerUser->id . '/edit');

        // assertions
        $this->assertController('users');
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
        $this->get('/admin/users/' . $this->ownerUser->id . '/edit');

        // assertions
        $this->assertController('users');
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
        $this->get('/admin/users/' . $this->ownerUser->id . '/edit');

        // assertions
        $this->assertController('users');
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
        $this->get('/admin/users/' . $this->ownerUser->id . '/edit');

        // assertions
        $this->assertController('users');
        $this->assertAction('edit');
    }


    // update

    public function testUpdateActionRedirectsToEditWhenLoggedInAsAdmin()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/users/' . $this->ownerUser->id, $this->getUserData());

        // assertions
        $this->assertController('users');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/users');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsMember()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/users/' . $this->ownerUser->id, $this->getUserData());

        // assertions
        $this->assertController('users');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/users/' . $this->ownerUser->id . '/edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsOwner()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/users/' . $this->ownerUser->id, $this->getUserData());

        // assertions
        $this->assertController('users');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/users/' . $this->ownerUser->id . '/edit');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testUpdateActionRedirectsToEditWhenLoggedInAsRandom()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->put('/admin/users/' . $this->ownerUser->id, $this->getUserData());

        // assertions
        $this->assertController('users');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/users/' . $this->ownerUser->id . '/edit');
    }


    // delete

    public function testDeleteActionWhenLoggedInAsAdminUserShowsUser()
    {
        $currentUser = $this->adminUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/users/' . $this->ownerUser->id);

        // assertions
        $this->assertController('users');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/users');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsEditorUserShowsUser()
    {
        $currentUser = $this->editorUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/users/' . $this->ownerUser->id);

        // assertions
        $this->assertController('users');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/users');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsOwnerUserShowsUser()
    {
        $currentUser = $this->ownerUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/users/' . $this->ownerUser->id);

        // assertions
        $this->assertController('users');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/users');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testDeleteActionWhenLoggedInAsRandomUserThrowsException()
    {
        $currentUser = $this->randomUser;
        $this->login($currentUser);

        // dispatch
        $this->delete('/admin/users/' . $this->ownerUser->id);

        // assertions
        $this->assertController('users');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/admin/users');
    }


    // data providers

    public function getUserData($data=array())
    {
        return array_merge( array(
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martyn@example.com', // invalid email
            'password' => 'mypass',
        ), $data );
    }
}
