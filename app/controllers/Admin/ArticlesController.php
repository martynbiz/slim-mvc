<?php

namespace App\Controller\Admin;

use SlimMvc\Http\Controller;
use App\Model\Article;
use App\Exception\NotFound as NotFoundException;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = $this->getService('model.article')->find();

        return $this->render('admin/articles/index.html', array(
            'articles' => $articles->toArray(),
        ));
    }

    public function show($id)
    {
        $article = $this->getService('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        return $this->render('admin/articles/show.html', array(
            'article' => $article->toArray(),
        ));
    }

    public function create()
    {
        return $this->render('admin/articles/create.html');
    }

    public function post()
    {
        $article = new Article( $this->getPost() );

        if ( $article->save() ) {
            return $this->redirect('/admin/articles');
        } else {
            // $this->flash( $article->getErrors() );
            return $this->create();
        }
    }

    public function edit($id)
    {
        $article = $this->getService('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        return $this->render('admin/articles/edit.html', array(
            'article' => array_merge($article->toArray(), $this->getPost()),
        ));
    }

    public function update($id)
    {
        $article = $this->getService('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        if ( $article->save( $this->getPost() ) ) {
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->flash( $article->getErrors() );
            return $this->edit($id);
        }
    }
}
