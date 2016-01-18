<?php

use MartynBiz\Mongo\MongoIterator;

use CrSrc\Model\Article;

class ArticlesControllerTests extends \CrSrc\Test\PHPUnit\TestCase
{
    /**
     * @var Article_stub
     */
    protected $articleMock;

    public function setUp()
    {
        parent::setUp();

        $container = $this->app->getContainer();

        // create mock articles
        // Create a stub for the SomeClass class.
        $this->articleMock = $this->getMockBuilder('CrSrc\Model\Article')
                     ->disableOriginalConstructor()
                     ->getMock();

        $container['model.article'] = $this->articleMock;
    }

    public function testIndexAction()
    {
        // Configure the stub.
        $this->articleMock
            ->expects( $this->once() )
            ->method('find')
            ->willReturn( new MongoIterator() ); // empty is fine

        $this->get('/admin/articles');

        // assertions

        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }

    public function testShowAction()
    {
        $article = new Article();
        $article->id = 1;

        // Configure the stub.
        $this->articleMock
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->willReturn($article); // empty is fine

        $this->get('/admin/articles/' . $article->id);

        // assertions

        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }

    public function testPostCreateArticleRedirectsOnCreate()
    {
        // TODO use data provider
        $type = Article::TYPE_ARTICLE;

        $article = new Article();
        $article->id = 1;
        $article->type = $type;

        $initialValues = array(
            // 'type' => $type,
        );

        // Configure the stub.
        $this->articleMock
            ->expects( $this->once() )
            ->method('create')
            ->with($initialValues)
            ->willReturn($article); // empty is fine

        $this->post('/admin/articles?type=article');

        // assertions

        $this->assertController('articles');
        $this->assertAction('post');
        $this->assertRedirects(); // don't have the id to check the correct url
    }

    public function testEditAction()
    {
        $article = new Article();
        $article->id = 1;

        // Configure the stub.
        $this->articleMock
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->with(array(
                'id' => $article->id
            ))
            ->willReturn($article); // empty is fine

        $this->get('/admin/articles/' . $article->id . '/edit');

        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    // public function testPutActionRedirectsToEdit()
    // {
    //     $this->put('/admin/articles/' . $this->article->id, $this->getArticleData());
    //
    //     // assertions
    //
    //     $this->assertController('articles');
    //     $this->assertAction('show');
    //     $this->assertRedirectsTo('/admin/articles/' . $this->article->id);
    // }

    // /**
    //  * @dataProvider getInvalidArticleData
    //  */
    // public function testPostActionWithInvalidParams($postData)
    // {
    //     $this->post('/admin/articles', $postData );
    //
    //     // assertions
    //
    //     $this->assertController('articles');
    //     $this->assertAction('create');
    // }


    // data providers

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
