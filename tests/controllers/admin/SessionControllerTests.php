<?php

use CrSrc\Test\PHPUnit\TestCase;

use CrSrc\Model\User;

class SessionControllerTests extends TestCase
{
    public function testLoginAction()
    {
        $this->get('/session/login');

        // assertions

        $this->assertController('session');
        $this->assertAction('login');
        $this->assertStatusCode(200);
    }

    // public function testPostActionWhenAuthReturnsSuccess()
    // {
    //     $this->post('/users', $this->getSessionData() );
    //
    //     // assertions
    //
    //     $this->assertRedirectsTo('/');
    // }
    //
    // public function testPostActionWhenAuthReturnsFail()
    // {
    //     $this->post('/users', $this->getArticleData() );
    //
    //     // assertions
    //
    //     $this->assertController('users');
    //     $this->assertAction('login');
    // }

    public function getSessionData($data=array())
    {
        return array_merge( array(
            'email' => 'martyn@example.com',
            'password' => 'mypass',
        ), $data );
    }
}
