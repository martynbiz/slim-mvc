<?php

use CrSrc\Test\PHPUnit\TestCase;

use Zend\Authentication\Result;
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

    public function testPostActionWhenAuthReturnsSuccess()
    {
        // user is authenticated
        $this->login( $this->user );

        $this->post('/session', $this->getLoginData() );

        // assertions

        $this->assertController('session');
        $this->assertAction('post');
        $this->assertRedirectsTo('/');
    }

    public function testPostActionWhenAuthReturnsFail()
    {
        $this->post('/session', $this->getLoginData() );

        // assertions

        $this->assertController('session');
        $this->assertAction('login');
        $this->assertStatusCode(200);
    }

    public function testLogoutCallsAuthClearIdentityThenRedirects()
    {
        $container = $this->app->getContainer();

        // by defaut, we'll make getIdentity return a null
        $container['auth']
            ->expects( $this->once() )
            ->method('clearIdentity');

        $this->post('/session', array(
            '_METHOD' => 'DELETE',
        ) );

        // assertions

        $this->assertController('session');
        $this->assertAction('delete');
        $this->assertRedirectsTo('/');
    }


    // data providers

    protected function getLoginData($data=array())
    {
        return array_merge( array(
            'email' => 'martyn@example.com',
            'password' => 'mypass',
        ), $data );
    }
}
