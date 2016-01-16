<?php

namespace CrSrc\Controller;

use SlimMvc\Http\Controller;
use CrSrc\Model\User;

class UsersController extends Controller
{
    public function create()
    {
        return $this->render('users/create.html', array(
            'params' => $this->getPost(),
        ));
    }

    public function post()
    {
        $user = new User( $this->getPost() );

        if ( $user->save() ) {
            return $this->redirect('/');
        } else {
            return $this->forward('create');
        }
    }
}
