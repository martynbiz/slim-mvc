<?php

namespace App\Controller;

use SlimMvc\Controller\Base;

class IndexController extends Base
{
    public function home()
    {
        $container = $this->app->getContainer();
        var_dump($container->get('model.article'));
        $this->render('index/index.twig');
    }

    public function contact()
    {
        $this->render('index/contact.twig');
    }
}
