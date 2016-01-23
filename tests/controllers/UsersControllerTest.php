<?php
namespace Tests\Controllers;

use Wordup\Model\User;

class UsersControllerTest extends \TestCase
{
    public function testCreateAction()
    {
        // =================================
        // dispatch

        $this->get('/users/create');

        // =================================
        // assertions

        $this->assertController('users');
        $this->assertAction('create');
        $this->assertStatusCode(200);
    }

    public function testPostActionRedirectsToHomeWithValidParams()
    {
        // =================================
        // dispatch

        $this->post('/users', $this->getPostData() );


        // =================================
        // assertions

        $this->assertController('users');
        $this->assertAction('post');
        $this->assertRedirectsTo('/');
    }

    /**
     * @dataProvider getInvalidPostData
     */
    public function testPostActionForwardsToCreateWithInvalidParams($params)
    {
        // =================================
        // dispatch

        $this->post('/users', $params);


        // =================================
        // assertions

        $this->assertController('users');
        $this->assertAction('create');
    }

    public function getPostData($data=array())
    {
        return array_merge( array(
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martyn@example.com',
            'password' => 'mypass',
        ), $data );
    }

    public function getInvalidPostData()
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
                    'email' => 'martyn@example', // invalid email
                    'password' => 'mypass',
                ),
            ),
        );
    }
}
