<?php
namespace App\Auth;

interface AuthInterface
{
    /**
     * Return true if the user is authenticated
     * @return boolean
     */
    public function isAuthenticated();

    /**
     * This is the identity (e.g. username) stored for this user
     * @return string
     */
    public function getIdentity();

    /**
     * Authenticate a user by their credentials
     * @return boolean
     */
    public function authenticate($username, $password);
}
