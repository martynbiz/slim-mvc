<?php

namespace CrSrc\Model;

use MartynBiz\Mongo;

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

        if (empty($this->data['first_name'])) {
            $this->setError('First name must be given');
        }

        if (empty($this->data['last_name'])) {
            $this->setError('Last name must be given');
        }

        if (empty($this->data['email'])) {
            $this->setError('Email must be given');
        }

        if (empty($this->data['password'])) {
            $this->setError('Last name must be given');
        }

        return empty($this->getErrors());
    }
}
