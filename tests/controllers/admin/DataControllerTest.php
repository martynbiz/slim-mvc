<?php
namespace Tests\Controllers\Admin;

class DataControllerTest extends \TestCase
{

    // ======================================
    // test routes when not authenticated redirect to login page

    public function testGetImportActionWhenLoggedInAsEditorUserShowsUserActionWhenNotAuthenticatedRedirectsToLogin()
    {
        $this->get('/admin/data/import');

        $this->assertRedirectsTo('/session/login');
    }


    // ======================================
    // test routes when authenticated

    public function testGetImportActionWhenLoggedInAsEditorUserShowsUserActionWhenLoggedInAsAdminUserShowsUsers()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->get('/admin/data/import');

        // assertions
        $this->assertController('data');
        $this->assertAction('import');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testGetImportActionWhenLoggedInAsEditorUserShowsUser()
    {
        $this->login($this->editorUser);

        // dispatch
        $this->get('/admin/data/import');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testGetImportActionWhenLoggedInAsEditorUserShowsUserActionWhenLoggedInAsOwnerUserShowsUser()
    {
        $this->login($this->ownerUser);

        // dispatch
        $this->get('/admin/data/import');
    }

    public function testPostImportActionWhenLoggedInAsEditorUserShowsUserActionWhenLoggedInAsAdminUserShowsUsers()
    {
        $this->login($this->adminUser);

        // dispatch
        $this->post('/admin/data/import');

        // assertions
        $this->assertController('data');
        $this->assertAction('import');
        $this->assertStatusCode(200);
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testPostImportActionWhenLoggedInAsEditorUserShowsUser()
    {
        $this->login($this->editorUser);

        // dispatch
        $this->post('/admin/data/import');
    }

    /**
     * @expectedException Wordup\Exception\PermissionDenied
     */
    public function testPostImportActionWhenLoggedInAsEditorUserShowsUserActionWhenLoggedInAsOwnerUserShowsUser()
    {
        $this->login($this->ownerUser);

        // dispatch
        $this->post('/admin/data/import');
    }
}
