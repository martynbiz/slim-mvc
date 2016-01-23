<?php
namespace Tests\Models;

use Wordup\Model\User;
use Wordup\Model\Article; // used for testing permission methods (e.g. canView)

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $user = new User();

        $this->assertTrue($user instanceof User);
    }

    public function testWhitelist()
    {
        $values = array(
            // whitelisted - e.g. title
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martyn@example.com',
            'password' => 'mypass',

            // not whitelisted - e.g. status
            'role' => 'admin',
        );

        $user = new User($values);

        $this->assertEquals($values['first_name'], $user->first_name);
        $this->assertEquals($values['last_name'], $user->last_name);
        $this->assertEquals($values['email'], $user->email);
        $this->assertEquals($values['password'], $user->password);

        $this->assertNotEquals($values['role'], @$user->role);
    }

    /**
     * @dataProvider getInvalidUserData
     */
    public function testValidateFailsWhenInvalidValuesSet($values)
    {
        $user = new User($values);

        $this->assertFalse( $user->validate() );
    }

    public function testAclMethodsReturnFalseWhenRoleNotSet()
    {
        $user = new User( $this->getUserData() );

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isEditor());
        $this->assertFalse($user->isEditor());
    }

    public function testIsAdminReturnsTrueAndOtherAclMethodsReturnFalse()
    {
        $user = new User( $this->getUserData() );

        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->isAdmin());

        $this->assertFalse($user->isEditor());
        $this->assertFalse($user->isMember());
    }

    public function testIsEditorReturnsTrueAndOtherAclMethodsReturnFalse()
    {
        $user = new User( $this->getUserData() );

        $user->role = User::ROLE_EDITOR;

        $this->assertTrue($user->isEditor());

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isMember());
    }

    public function testIsMemberReturnsTrueAndOtherAclMethodsReturnFalse()
    {
        $user = new User( $this->getUserData() );

        $user->role = User::ROLE_MEMBER;

        $this->assertTrue($user->isMember());

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isEditor());
    }


    // canView

    public function testCanViewReturnsTrueArticleWhenAdminUser()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->canView($article));
    }

    public function testCanViewReturnsTrueArticleWhenEditorUser()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->role = User::ROLE_EDITOR;

        $this->assertTrue($user->canView($article));
    }

    public function testCanViewReturnsTrueArticleWhenMemberIsOwner()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->id = 101;
        $user->role = User::ROLE_MEMBER;

        $article->author = $user;

        $this->assertTrue($user->canView($article));
    }


    // canEdit

    public function testCanEditReturnsTrueArticleWhenAdminUser()
    {
        $user = new User();
        $article = new Article();

        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->canEdit($article));
    }

    public function testCanEditReturnsTrueArticleWhenEditorUser()
    {
        $user = new User();
        $article = new Article();

        $user->role = User::ROLE_EDITOR;

        $this->assertTrue($user->canEdit($article));
    }

    public function testCanEditReturnsTrueArticleWhenMemberIsOwner()
    {
        $user = new User();
        $article = new Article();

        $user->id = 101;
        $user->role = User::ROLE_MEMBER;

        $article->author = $user;

        $this->assertTrue($user->canEdit($article));
    }


    // canDelete

    public function testCanDeleteReturnsTrueArticleWhenAdminUser()
    {
        $user = new User();
        $article = new Article();

        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->canDelete($article));
    }

    public function testCanDeleteReturnsTrueArticleWhenEditorUser()
    {
        $user = new User();
        $article = new Article();

        $user->role = User::ROLE_EDITOR;

        $this->assertTrue($user->canDelete($article));
    }

    public function testCanDeleteReturnsTrueArticleWhenMemberIsOwner()
    {
        $user = new User();
        $article = new Article();

        $user->id = 101;
        $user->role = User::ROLE_MEMBER;

        $article->author = $user;

        $this->assertTrue($user->canDelete($article));
    }


    // canSubmit

    public function testCanSubmitReturnsTrueArticleWhenAdminUser()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->canSubmit($article));
    }

    public function testCanSubmitReturnsTrueArticleWhenEditorUser()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->role = User::ROLE_EDITOR;

        $this->assertTrue($user->canSubmit($article));
    }

    public function testCanSubmitReturnsTrueArticleWhenMemberIsOwner()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->id = 101;
        $user->role = User::ROLE_MEMBER;

        $article->author = $user;

        $this->assertTrue($user->canSubmit($article));
    }


    // canApprove

    public function testCanApproveReturnsTrueArticleWhenAdminUser()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->role = User::ROLE_ADMIN;

        $this->assertTrue($user->canApprove($article));
    }

    public function testCanApproveReturnsTrueArticleWhenEditorUser()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->role = User::ROLE_EDITOR;

        $this->assertTrue($user->canApprove($article));
    }

    public function testCanApproveReturnsTrueArticleWhenMemberIsOwner()
    {
        $user = new User( $this->getUserData() );
        $article = new Article();

        $user->id = 101;
        $user->role = User::ROLE_MEMBER;

        $article->author = $user;

        $this->assertTrue($user->canApprove($article));
    }


    public function testIsOwnerOfReturnsTrueWhenUserIsOwner()
    {
        $user = new User();
        $article = new Article();

        $user->id = 101;
        $article->author = $user;

        $this->assertTrue($user->canView($article));
    }

    public function testIsOwnerOfReturnsFalseWhenUserIsNotOwner()
    {
        $user = new User();
        $owner = new User();
        $article = new Article();

        $user->id = 101;
        $owner->id = 102;
        $article->author = $owner;

        $this->assertFalse($user->canView($article));
    }
    public function testIsOwnerOfReturnsFalseWhenIdNotSet()
    {
        $user = new User();
        $owner = new User();
        $article = new Article();

        $article->author = $owner;

        $this->assertFalse($user->canView($article));
    }


    public function getUserData($data=array())
    {
        return array_merge( array(
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martyn@example.com',
            'password' => 'mypass',
        ), $data );
    }

    public function getInvalidUserData()
    {
        return array(
            array(
                array(
                    // 'first_name' => 'Martyn',
                    'last_name' => 'Bissett',
                    'email' => 'martyn@example.com',
                    'password' => 'mypass',
                ),
            ),
            array(
                array(
                    'first_name' => 'Martyn',
                    // 'last_name' => 'Bissett',
                    'email' => 'martyn@example.com',
                    'password' => 'mypass',
                ),
            ),
            array(
                array(
                    'first_name' => 'Martyn',
                    'last_name' => 'Bissett',
                    // 'email' => 'martyn@example.com',
                    'password' => 'mypass',
                ),
            ),
            array(
                array(
                    'first_name' => 'Martyn',
                    'last_name' => 'Bissett',
                    'email' => 'martyn@example.com',
                    // 'password' => 'mypass',
                ),
            ),
            array(
                array(
                    'first_name' => 'Martyn',
                    'last_name' => 'Bissett',
                    'email' => 'martyn@', // invalid email
                    'password' => 'mypass',
                ),
            ),
        );
    }
}
