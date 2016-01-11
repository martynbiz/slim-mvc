<?php

namespace App\Controller;

class ArticlesController extends SlimController
{
    public function index()
    {
        $this->render('articles/index.twig');
    }

    public function show()
    {
        $this->render('articles/show.twig');
    }

    public function create()
    {
        $this->render('articles/new.twig');
    }

    public function edit()
    {
        $this->render('articles/edit.twig');
    }
}
