<?php

namespace CrSrc\Controller;

use SlimMvc\Http\Controller;

class BaseController extends Controller
{
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * Render the view from within the controller
     * @param string $file Name of the template/ view to render
     * @param array $args Additional varables to pass to the view
     * @param Response?
     */
    protected function render($file, $args=array())
    {
        // before we pass on operation to the SlimMvc\Http\Controller::render, we'll
        // set some useful variables for the view (e.g. currently logged in user)
        $args['current_user'] = $this->getCurrentUser();
        if ($args['current_user']) {
            $args['current_user'] = $args['current_user']->toArray();
        }

        return parent::render($file, $args);
    }

    /**
     * Get the currently authentication user (if authenticated)
     * @return User\null
     */
    protected function getCurrentUser()
    {
        // get the identity (email) from the auth service
        // return null if not set
        $identity = $this->get('auth')->getIdentity();
        if (! $identity) {
            return null;
        }

        // lookup the user by identity
        $this->currentUser = $this->get('model.user')->findOne(array(
            'email' => $identity,
        ));

        return $this->currentUser;
    }
}
