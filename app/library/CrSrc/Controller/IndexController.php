<?php

namespace CrSrc\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        return $this->render('index/index.html');
    }

    public function contact()
    {
        return $this->render('index/contact.html');
    }
}
