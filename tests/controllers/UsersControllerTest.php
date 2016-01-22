<?php

use App\Test\PHPUnit\TestCase;

use App\Model\User;

class UsersControllerTests extends TestCase
{
    public function testCreateAction()
    {
        $this->get('/users/create');

        // assertions

        $this->assertController('users');
        $this->assertAction('create');
        $this->assertStatusCode(200);
    }

    public function testPostActionRedirectsToHomeWhenCreateSuccess()
    {
        $user = $this->generateUserStub();


        // =================================
        // mock method stack, in order

        // Configure the stub.
        $this->container['model.user']
            ->expects( $this->once() )
            ->method('create')
            ->with( $this->getUserData() )
            ->willReturn($user); // <--------- success


        // =================================
        // dispatch

        $this->post('/users', $this->getUserData() );


        // =================================
        // assertions

        $this->assertController('users');
        $this->assertAction('post');
        $this->assertRedirectsTo('/');
    }

    public function testPostActionForwardsToCreateWhenCreateFails()
    {
        // =================================
        // mock method stack, in order

        // Configure the stub.
        $this->container['model.user']
            ->expects( $this->once() )
            ->method('create')
            ->with( $this->getUserData() )
            ->willReturn(false); // <--------- failed


        // =================================
        // dispatch

        $this->post('/users', $this->getUserData() );


        // =================================
        // assertions

        $this->assertController('users');
        $this->assertAction('create');
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
}
