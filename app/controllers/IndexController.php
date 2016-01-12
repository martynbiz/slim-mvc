<?php

namespace App\Controller;

use SlimMvc\Controller\Base;

class IndexController extends Base
{
    public function home()
    {
        $this->render('index/index.html');
    }

    public function contact()
    {
        $this->render('index/contact.html');
    }
}
