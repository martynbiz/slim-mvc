<?php

use CrSrc\Model\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $user = new User();

        $this->assertTrue($user instanceof User);
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
