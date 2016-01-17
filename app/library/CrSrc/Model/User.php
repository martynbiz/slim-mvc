<?php

namespace CrSrc\Model;

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
}
