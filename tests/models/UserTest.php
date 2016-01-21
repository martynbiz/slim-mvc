<?php

use App\Model\User;

class UserTest extends PHPUnit_Framework_TestCase
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
     * @dataProvider getUserValues
     */
    public function testValidateFailsWhenInvalidValuesSet($values)
    {
        $user = new User($values);

        $this->assertFalse( $user->validate() );
    }

    public function getUserValues()
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
