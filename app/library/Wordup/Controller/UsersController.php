<?php
namespace Wordup\Controller;

use Wordup\Model\User;

class UsersController extends BaseController
{
    public function show($id)
    {
        $user = $this->get('model.user')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        return $this->render('users.create', array(
            'user' => $user,
        ));
    }

    public function create()
    {
        return $this->render('users.create', array(
            'params' => $this->getPost(),
        ));
    }

    public function post()
    {
        $params = $this->getPost();
        $user = $this->get('model.user')->factory( $params );

        // for security reasons, role isn't on the whitelist for mass assignment
        // but we can set it via property assignment. Default to ROLE_MEMBER
        $user->role = User::ROLE_MEMBER;

        // generate the password
        $user->password = User::encryptPassword(@$params['password']);

        if ($user->save()) {
            $this->get('auth')->authenticate($params['email'], $params['password']);
            return $this->redirect('/');
        } else {
            $this->get('flash')->addMessage('errors', $user->getErrors());
            return $this->forward('create');
        }
    }
}
