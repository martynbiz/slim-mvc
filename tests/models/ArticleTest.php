<?php

use Wordup\Model\Article;

class ArticleTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $article = new Article();

        $this->assertTrue($article instanceof Article);
    }

    public function testWhitelist()
    {
        $values = array(
            // whitelisted - e.g. title
            'title' => 'The title',
            'slug' => 'slug',

            // not whitelisted - e.g. status
            'status' => 2,
            'type' => 'ARTICLE',
            'author' => 'Martyn',
        );

        $article = new Article($values);

        $this->assertEquals($values['title'], $article->title);
        $this->assertEquals($values['slug'], $article->slug);

        $this->assertNotEquals($values['status'], @$article->status);
        $this->assertNotEquals($values['type'], @$article->type);
        $this->assertNotEquals($values['author'], @$article->author);
    }
}
