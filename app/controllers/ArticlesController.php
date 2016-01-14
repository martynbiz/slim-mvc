<?php

namespace App\Controller;

use SlimMvc\Http\Controller;

class ArticlesController extends Controller
{
    public function index()
    {
        $this->render('articles/index.html');
    }

    public function show($id)
    {
        $this->render('articles/show.html');
    }

    public function create()
    {
        $this->render('articles/create.html');
    }

    public function edit($id)
    {
        $this->render('articles/edit.html');
    }
}
