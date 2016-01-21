<?php
namespace App\Controller;

use MartynBiz\Slim3Controller\Controller;

abstract class BaseController extends Controller
{
    /**
     * @var User
     */
    protected $currentUser;

    /**
     * Render the view from within the controller
     * @param string $filepath Name of the template/ view to render
     * @param array $data Additional varables to pass to the view
     * @param Response?
     */
    protected function render($filepath, $data=array())
    {
        // before we pass on operation to the SlimMvc\Http\Controller::render, we'll
        // set some useful variables for the view (e.g. currently logged in user)

        // attach the current user to the view variables
        $currentUser = $this->getCurrentUser();
        if ($currentUser) {
            $data['current_user'] = $currentUser->toArray();

            // roles - could opt for current_user.role == "admin" but
            // these are much more pleasant in the views :)
            $data['current_user']['is_admin'] = $currentUser->isAdmin();
            $data['current_user']['is_editor'] = $currentUser->isEditor();
            $data['current_user']['is_member'] = $currentUser->isMember();
        }

        // attach any flash messages
        $data['flash_messages'] = $this->get('flash')->flushMessages();

        return parent::render($filepath, $data);
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
