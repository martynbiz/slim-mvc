<?php

namespace App\Controller\Admin;

use App\Controller\SlimController;

class ArticlesController extends SlimController
{
    public function index()
    {
        $this->render('admin/articles/index.twig');
    }

    public function show()
    {
        $this->render('admin/articles/show.twig');
    }

    public function create()
    {
        $this->render('admin/articles/new.twig');
    }

    public function edit()
    {
        $this->render('admin/articles/edit.twig');
    }
}
