<?php
namespace Wordup\Auth;

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
     * @param string $username Whatever the identity of the user
     * @return boolean
     */
    public function authenticate($identity, $password);

    /**
     * Authenticate a user by their credentials
     * @return User
     */
    public function getCurrentUser();
}
