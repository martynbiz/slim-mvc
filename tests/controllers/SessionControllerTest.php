<?php

use App\Test\PHPUnit\TestCase;

use Zend\Authentication\Result;
use App\Model\User;

class SessionControllerTests extends TestCase
{
    public function testLoginAction()
    {
        $this->get('/session/login');


        // =================================
        // assertions

        $this->assertController('session');
        $this->assertAction('login');
        $this->assertStatusCode(200);
    }

    public function testPostActionRedirectsToHomeWhenAuthenticates()
    {
        $user = $this->generateUserStub();
        $userData = $this->getLoginData();


        // =================================
        // mock method stack, in order

        // assert authenticate is called with email/password
        $this->container['auth']
            ->expects( $this->once() )
            ->method('authenticate')
            ->with($userData['email'], $userData['password']);

        // this will create a simulated authenticated session
        $this->login($user);


        // =================================
        // dispatch

        $this->post('/session', $this->getLoginData() );


        // =================================
        // assertions

        $this->assertController('session');
        $this->assertAction('post');
        $this->assertRedirectsTo('/');
    }

    public function testPostActionForwardsToLoginWhenAuthenticateFails()
    {
        $user = $this->generateUserStub();
        $userData = $this->getLoginData();


        // =================================
        // mock method stack, in order

        // assert authenticate is called with email/password
        $this->container['auth']
            ->expects( $this->once() )
            ->method('authenticate')
            ->with($userData['email'], $userData['password']);


        // =================================
        // dispatch

        $this->post('/session', $this->getLoginData() );


        // =================================
        // assertions

        $this->assertController('session');
        $this->assertAction('login');
    }

    public function testLogoutClearsIdentityThenRedirects()
    {

        // =================================
        // mock method stack, in order

        $this->container['auth']
            ->expects( $this->once() )
            ->method('clearIdentity');


        // =================================
        // dispatch

        $this->post('/session', array(
            '_METHOD' => 'DELETE',
        ) );


        // =================================
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
