<?php
namespace Wordup\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        // get featured articles from cache
        $cacheId = 'homepage_featured';
        if (! $featured = $this->get('cache')->get($cacheId)) {
            $featured = $this->get('model.article')->find();
            $this->get('cache')->set($cacheId, $featured, 3600);
        }

        // get tags from cache
        $cacheId = 'tags';
        if (! $tags = $this->get('cache')->get($cacheId)) {
            $tags = $this->get('model.tag')->find();
            $this->get('cache')->set($cacheId, $tags, 3600);
        }

        return $this->render('index/index.html', array(
            'featured_articles' => $featured->toArray(),
            'tags' => $tags->toArray(),
        ));
    }

    public function contact()
    {
        return $this->render('index/contact.html');
    }
}
