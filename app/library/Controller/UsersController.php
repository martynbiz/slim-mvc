<?php
namespace App\Controller;

use App\Model\User;

class UsersController extends BaseController
{
    public function show()
    {

    }

    public function create()
    {
        return $this->render('users/create.html', array(
            'params' => $this->getPost(),
        ));
    }

    public function post()
    {
        $user = $this->get('model.user')->create( $this->getPost() );

        if ($user) {
            return $this->redirect('/');
        } else {
            return $this->forward('create');
        }
    }

    public function edit()
    {

    }

    public function update()
    {

    }
}
