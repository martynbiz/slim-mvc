<?php
namespace Wordup\Controller;

class SessionController extends BaseController
{
    public function login()
    {
        return $this->render('session/login.html');
    }

    public function post()
    {
        $params = $this->getPost();
        $authService = $this->get('auth');

        $authService->authenticate($params['email'], $params['password']);

        if ($authService->isAuthenticated()) {
            $this->get('flash')->addMessage('success', 'You have successfully logged in.');
            return $this->redirect('/');
        } else {
            $this->get('flash')->addMessage('errors', array(
                'Invalid username/password. Please try again.'
            ));
            return $this->forward('login');
        }
    }

    public function delete()
    {
        $this->get('auth')->clearIdentity();

        return $this->redirect('/');
    }
}
