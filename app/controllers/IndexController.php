<?php

namespace App\Controller;

class IndexController extends SlimController
{
    public function home()
    {
        $this->render('index/index.twig');
    }

    public function contact()
    {
        $this->render('index/contact.twig');
    }
}
