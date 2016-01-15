<?php

use SlimMvc\Test\PHPUnit\TestCase;

use CrSrc\Model\Article;

class ArticlesControllerTests extends TestCase
{
    public function setUp()
    {
        // create fixtures
        $this->article = new Article( array(
            'title' => 'A long time ago in a galaxy far far away...',
            'description' => '...'
        ) );

        $this->article->save();
    }

    public function tearDown()
    {
        // create fixtures
        $this->article->getConnection()->delete('articles');
    }

    public function testIndexAction()
    {
        $this->get('/admin/articles');

        // assertions

        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }

    public function testShowAction()
    {
        $this->get('/admin/articles/' . $this->article->id);

        // assertions

        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }

    public function testCreateAction()
    {
        $this->get('/admin/articles/create');

        // assertions

        $this->assertController('articles');
        $this->assertAction('create');
        $this->assertStatusCode(200);
    }

    public function testEditAction()
    {
        $this->get('/admin/articles/' . $this->article->id . '/edit');

        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    public function testPostActionWithValidParams()
    {
        $this->post('/admin/articles', $this->getArticleData() );

        // assertions

        $this->assertController('articles');
        $this->assertAction('post');
        $this->assertRedirectsTo('/admin/articles');
    }

    /**
     * @dataProvider getInvalidArticleData
     */
    public function testPostActionWithInvalidParams($postData)
    {
        $this->post('/admin/articles', $postData );

        // assertions

        $this->assertController('articles');
        $this->assertAction('create');
    }

    public function getArticleData($data=array())
    {
        return array_merge( array(
            'title' => 'A long time ago in a galaxy far far away',
            'description' => '<p>Hello world!</p>',
        ), $data );
    }

    public function getInvalidArticleData()
    {
        return array(
            array(
                array(
                    'title' => 'A long time ago in a galaxy far far away',
                    // 'description' => '<p>Hello world!</p>', // missing description
                ),
            ),
            array(
                array(
                    // 'title' => 'A long time ago in a galaxy far far away', // missing title
                    'description' => '<p>Hello world!</p>',
                ),
            ),
        );
    }
}
