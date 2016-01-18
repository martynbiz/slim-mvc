<?php

namespace CrSrc\Controller\Admin;

use SlimMvc\Http\Controller;
use CrSrc\Model\Article;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = $this->get('model.article')->find();

        return $this->render('admin/articles/index.html', array(
            'articles' => $articles->toArray(),
        ));
    }

    public function show($id)
    {
        $article = $this->get('model.article')->findOneOrFail(array(
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
            $this->get('flash')->addMessage('success', 'Article created successfully');
            return $this->redirect('/admin/articles'); // TODO redirect to show action
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->forward('create');
        }
    }

    public function edit($id)
    {
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        return $this->render('admin/articles/edit.html', array(
            'article' => array_merge($article->toArray(), $this->getPost()),
        ));
    }

    public function update($id)
    {
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        if ( $article->save( $this->getPost() ) ) {
            $this->get('flash')->addMessage('success', 'Article updated successfully');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->edit($id);
        }
    }

    public function delete($id)
    {
        $article = $this->get('model.article')->findOneOrFail(array(
            'id' => (int) $id,
        ));

        if ( $article->delete() ) {
            $this->get('flash')->addMessage('success', 'Article deleted successfully');
            return $this->redirect('/admin/articles/' . $id);
        } else {
            $this->get('flash')->addMessage('errors', $article->getErrors());
            return $this->edit($id);
        }
    }
}
