<?php

namespace App\Model;

use MartynBiz\Mongo;
use MartynBiz\Validator;

/**
 *
 */
class User extends Mongo
{
    // collection this model refers to
    protected $collection = 'users';

    // define on the fields that can be saved
    protected $whitelist = array(
        'first_name',
        'last_name',
        'email',
        'password',
    );

    public function validate()
    {
        $this->resetErrors();

        $validator = new Validator($this->data);

        $validator->check('first_name')
            ->isNotEmpty('First name is missing');

        $validator->check('last_name')
            ->isNotEmpty('First name is missing');

        $validator->check('email')
            ->isNotEmpty('Email address is missing')
            ->isEmail('Invalid email address');

        $validator->check('password')
            ->isNotEmpty('Password is missing');

        // update the model's errors with the validators
        $this->setError( $validator->getErrors() );

        return empty($this->getErrors());
    }

    // ===============================
    // ACL methods
    // The following methods are to grant an authenticated user access to
    // a particular role on a resource (e.g. view a given article ). These are
    // also quite readable when written out e.g. $user->canEdit($article)

    // roles

    /**
     * Return true if "admin" user
     * @return boolean
     */
    public function isAdmin()
    {
        // TODO when implemented, test
        return true;
    }

    /**
     * Return true if "editor" user
     * @return boolean
     */
    public function isEditor()
    {
        // TODO when implemented, test
        return false;
    }

    /**
     * Return true if "member" user
     * @return boolean
     */
    public function isMember()
    {
        // TODO when implemented, test
        return false;
    }

    // permissions

    /**
     * Return true if user can view a given resource
     */
    public function canView($resource)
    {
        return true;
    }

    /**
     * Return true if user can view a given resource
     */
    public function canEdit($resource)
    {
        return true;
    }

    /**
     * Return true if user can view a given resource
     */
    public function canDelete($resource)
    {
        return true;
    }

    /**
     * Return true if user can view a given resource
     */
    public function canSubmit($article)
    {
        return true;
    }

    /**
     * Return true if user can view a given resource
     */
    public function canApprove($article)
    {
        return true;
    }
}
