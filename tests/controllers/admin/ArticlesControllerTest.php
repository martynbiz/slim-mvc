<?php

use MartynBiz\Mongo\MongoIterator;

use CrSrc\Model\Article;

class ArticlesControllerTests extends \CrSrc\Test\PHPUnit\TestCase
{
    /**
     * @var App's container
     */
    protected $articleMock;

    public function setUp()
    {
        parent::setUp();

        $container = $this->app->getContainer();

        // Create a stub for the Article class.
        $this->articleMock = $this->generateArticleStub();

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
        $type = Article::TYPE_ARTICLE;

        $article = new Article();
        $article->id = 1; // required for redirect
        $article->type = $type;

        $initialValues = array(
            'type' => $type,
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
        $article->id = 67;

        // Configure the stub.
        $this->articleMock
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->with(array(
                'id' => $article->id,
            ))
            ->willReturn($article); // empty is fine

        $this->get('/admin/articles/' . $article->id . '/edit');

        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    public function testPutActionRedirectsToEdit()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();

        // Mock the model the dependency
        $this->articleMock
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->with(array(
                'id' => 99,
            ))
            ->willReturn($article);

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with( $this->getArticleData() )
            ->willReturn(true);

        $this->put('/admin/articles/99', $this->getArticleData());

        // assertions

        $this->assertController('articles');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/articles/99/edit');
    }

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

    /**
     * Will generate an article stub for use in this test
     */
    public function generateArticleStub()
    {
        return $this->getMockBuilder('CrSrc\Model\Article')
                     ->disableOriginalConstructor()
                     ->getMock();
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
