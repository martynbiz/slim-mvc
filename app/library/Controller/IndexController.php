<?php
namespace App\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        // get featured articles from cache
        $cacheId = 'homepage_featured';
        if (! $featuredArticles = $this->get('cache')->get($cacheId)) {
            $featuredArticles = $this->get('model.article')->find();
            $this->get('cache')->set($cacheId, $featuredArticles, 3600);
        }

        // get popular articles from cache
        $cacheId = 'homepage_popular';
        if (! $popularArticles = $this->get('cache')->get($cacheId)) {
            $popularArticles = $this->get('model.article')->find();
            $this->get('cache')->set($cacheId, $popularArticles, 3600);
        }

        return $this->render('index/index.html', array(
            'featured_articles' => $featuredArticles->toArray(),
            'popular_articles' => $popularArticles->toArray(),
        ));
    }

    public function contact()
    {
        return $this->render('index/contact.html');
    }
}
