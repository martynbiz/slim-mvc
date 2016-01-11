<?php

namespace App\Controller;

use SlimMvc\Controller\Base;

class ArticlesController extends Base
{
    public function index()
    {
        $this->render('articles/index.twig');
    }

    public function show($id)
    {
        $this->render('articles/show.twig');
    }

    public function create()
    {
        $this->render('articles/create.twig');
    }

    public function edit($id)
    {
        $this->render('articles/edit.twig');
    }
}
