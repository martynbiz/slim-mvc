<?php

namespace CrSrc;

/**
 * This is a abstract layer between the third-party means we choose to authenticate
 * and the application
 */

use Zend\Authentication\Result;

use CrSrc\Auth\AuthInterface;
use CrSrc\Auth\Adapter\Mongo as AuthAdapter;

class Auth implements AuthInterface
{
    /**
     * @var ZendService
     */
    protected $authService;

    /**
     * We need to pass in the auth library that we're using
     */
    public function __construct($authService)
    {
        $this->authService = $authService;
    }


    /**
     * Return true if the user is authenticated
     * @return boolean
     */
    public function isAuthenticated()
    {
        $identity = $this->getIdentity();

        return (! is_null($identity));
    }

    /**
     * This is the identity (e.g. username) stored for this user
     * @return string
     */
    public function getIdentity()
    {
        return $this->authService->getIdentity();
    }

    /**
     * Clear the identity of the user (logout)
     * @return void
     */
    public function clearIdentity()
    {
        return $this->authService->clearIdentity();
    }

    /**
     * This is the identity (e.g. username) stored for this user
     * @return string
     */
    public function authenticate($username, $password)
    {
        $adapter = new AuthAdapter($username, $password);
        $result = $this->authService->authenticate($adapter);

        if ($result->getCode() === Result::SUCCESS) {
            return true;
        } else {
            return false;
        }
    }
}