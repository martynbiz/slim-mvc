<?php

namespace CrSrc\Controller;

use SlimMvc\Http\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->render('index/index.html');
    }

    public function contact()
    {
        $this->render('index/contact.html');
    }
}
