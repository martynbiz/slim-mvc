<?php
namespace App\Auth\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

use App\Model\User;

class Mongo implements AdapterInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var App\Model\User
     */
    protected $model;

    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username, $password, User $model)
    {
        $this->username = $username;
        $this->password = $password;
        $this->model = $model;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        // look up $user from the database
        $user = $this->model->findOne( array(
            'email' => $this->username,
            'password' => $this->password,
        ) );

        // if a user was found, return the appropriate Result
        if ($user) {
            return new Result(
                Result::SUCCESS,
                $this->username,
                array()
            );
        } else {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                array()
            );
        }
    }

    /**
     * Get an instance of the model
     */
    protected function getModel()
    {
        return new User();
    }
}
