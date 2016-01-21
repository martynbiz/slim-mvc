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

    public function testPostActionWithValidParams()
    {
        $this->post('/users', $this->getUserData() );

        // assertions

        $this->assertController('users');
        $this->assertAction('post');
        $this->assertRedirectsTo('/');
    }

    /**
     * @dataProvider getInvalidArticleData
     */
    public function testPostActionWithInvalidParams($postData)
    {
        $this->post('/users', $postData );

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

    public function getInvalidArticleData()
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
        );
    }
}
