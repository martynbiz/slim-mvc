<?php

namespace CrSrc\Controller;

use SlimMvc\Http\Controller;
use Zend\Authentication\Result;
use CrSrc\Auth\Adapter as AuthAdapter;

class SessionController extends Controller
{
    public function login()
    {
        $this->render('session/login.html');
    }

    public function post()
    {
        $params = $this->getPost();

        $adapter = new AuthAdapter($params['username'], $params['password']);
        $result = $this->get('auth')->authenticate($adapter);

        if ($result->getCode() === Result::SUCCESS) {
            $this->redirect('/');
        } else {
            $this->forward('login');
        }
    }

    public function logout()
    {
        $this->render('session/logout.html');
    }

    public function delete()
    {

    }
}
