<?php

namespace CrSrc\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        // get articles from cache
        $cacheId = 'homepage_articles';
        if (!$articles = $this->get('cache')->get($cacheId)) {
            $articles = $this->get('model.article')->find();
            $this->get('cache')->set($cacheId, $articles, 3600);
        }

        return $this->render('index/index.html', array(
            'articles' => $articles->toArray(),
        ));
    }

    public function contact()
    {
        return $this->render('index/contact.html');
    }
}
