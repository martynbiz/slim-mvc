<?php

namespace CrSrc\Controller;

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
            return $this->redirect('/');
        } else {
            return $this->forward('login');
        }
    }

    public function delete()
    {
        $this->get('auth')->clearIdentity();

        return $this->redirect('/');
    }
}
