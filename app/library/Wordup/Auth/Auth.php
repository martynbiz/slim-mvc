<?php

namespace Wordup\Auth;

/**
 * This is a abstract layer between the third-party means we choose to authenticate
 * and the application
 */

use Zend\Authentication\Result;
use Wordup\Auth\Adapter\Mongo as AuthAdapter;

use Wordup\Model\User;

class Auth implements AuthInterface
{
    /**
     * @var ZendService
     */
    protected $authService;

    /**
     * @var User
     */
    protected $currentUser;

    /**
     * @var User
     */
    protected $userModel;

    /**
     * We need to pass in the auth library that we're using
     */
    public function __construct($authService, $userModel)
    {
        // authService will interact with the session (e.g. get identity)
        $this->authService = $authService;

        // user model will be used to retreive the user from the users collections
        $this->userModel = $userModel;
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
    public function authenticate($identity, $password)
    {
        $adapter = new AuthAdapter($identity, $password, new User());
        $result = $this->authService->authenticate($adapter);

        if ($result->getCode() === Result::SUCCESS) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This is the identity (e.g. username) stored for this user
     * @return string
     */
    public function getCurrentUser()
    {
        if (! $this->currentUser) {
            // get the identity (email) from the auth service
            // return null if not set
            $identity = $this->getIdentity();
            if (! $identity) {
                return null;
            }

            // lookup the user by identity
            $this->currentUser = $this->userModel->findOne(array(
                'email' => $identity,
            ));
        }

        return $this->currentUser;
    }
}
