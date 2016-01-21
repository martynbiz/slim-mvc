<?php

use MartynBiz\Mongo\MongoIterator;

use App\Model\Article;

class ArticlesControllerTests extends \App\Test\PHPUnit\TestCase
{
    /**
     * @var App's container
     */
    protected $articleStub;

    public function setUp()
    {
        parent::setUp();

        // Create a stub for the Article class.
        $this->articleStub = $this->generateArticleStub();

        $this->container['model.article'] = $this->articleStub;
    }

    public function testIndexAction()
    {
        // =================================
        // mock method stack, in order

        $this->articleStub
            ->expects( $this->once() )
            ->method('find')
            ->willReturn( new MongoIterator() ); // empty is fine


        // =================================
        // dispatch

        $this->get('/admin/articles');


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }

    public function testShowAction()
    {
        $article = $this->generateArticleStub();

        // =================================
        // mock method stack, in order

        $this->articleStub
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->willReturn($article); // empty is fine


        // =================================
        // dispatch

        $this->get('/admin/articles/1');


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('show');
        $this->assertStatusCode(200);
    }


    // post

    public function testPostCreateArticleRedirectsToEditWhenSaveSuccess()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Configure the stub.
        $this->articleStub
            ->expects( $this->once() )
            ->method('factory')
            ->willReturn($article); // empty is fine

        // Ensure that new articles are initiated as DRAFT articles
        $article
            ->expects( $this->at(0) )
            ->method('set')
            ->with('status', Article::STATUS_DRAFT);

        // Ensure that new articles are initiated with type
        $article
            ->expects( $this->at(1) )
            ->method('set')
            ->with('type', Article::TYPE_ARTICLE);

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with()
            ->willReturn(true); // <------ success

        // Mock the response from save of $article
        $article
            ->method('get')
            ->with('id')
            ->willReturn(99);

        // =================================
        // dispatch

        $this->post('/admin/articles?type=' . Article::TYPE_ARTICLE);

        // assertions

        $this->assertController('articles');
        $this->assertAction('post');
        $this->assertRedirectsTo('/admin/articles/99/edit');
    }

    public function testPostCreateArticleForwardsToIndexWhenSaveFails()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Configure the stub.
        $this->articleStub
            ->expects( $this->once() )
            ->method('factory')
            ->willReturn($article); // empty is fine

        // Ensure that new articles are initiated as DRAFT articles
        $article
            ->expects( $this->at(0) )
            ->method('set')
            ->with('status', Article::STATUS_DRAFT);

        // Ensure that new articles are initiated with type
        $article
            ->expects( $this->at(1) )
            ->method('set')
            ->with('type', Article::TYPE_ARTICLE);

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with()
            ->willReturn(false); // <------ failed

        // getErrors will be called after the fail, return some/any array
        $article
            ->method('getErrors')
            ->willReturn( array('example' => 'Bad example') );

        // toArray will be called when we forward to index action
        // we can simply return $this->getArticleData()
        $article
            ->method('toArray')
            ->willReturn( $this->getArticleData() );

        // This will be called when we return back to the index action
        // an empty MongoIterator is fine for testing
        $this->articleStub
            ->expects( $this->once() )
            ->method('find')
            ->willReturn( new MongoIterator() );

        // =================================
        // dispatch

        $this->post('/admin/articles?type=article');

        // assertions

        $this->assertController('articles');
        $this->assertAction('index');
    }

    public function testEditAction()
    {
        $article = new Article();
        $article->id = 67;

        // =================================
        // mock method stack, in order

        // Configure the stub.
        $this->articleStub
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->with(array(
                'id' => $article->id,
            ))
            ->willReturn($article); // empty is fine

        // =================================
        // dispatch

        $this->get('/admin/articles/' . $article->id . '/edit');

        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
        $this->assertStatusCode(200);
    }

    public function testPutActionRedirectsToEditWhenSaveSuccess()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();

        // =================================
        // mock method stack, in order

        // Mock the model the dependency
        $this->articleStub
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
            ->willReturn(true); // <------ success

        // =================================
        // dispatch

        $this->put('/admin/articles/99', $this->getArticleData());

        // assertions

        $this->assertController('articles');
        $this->assertAction('update');
        $this->assertRedirectsTo('/admin/articles/99/edit');

        // as this is a redirect, we haven't flushed the messages yet. another
        // thing we can test :)
        $this->assertTrue( $this->container['flash']->has('success') );
    }

    public function testPutActionForwardsToEditWhenSaveFails()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Mock the model the dependency
        $this->articleStub
            ->expects( $this->exactly(2) ) // in update, and again in edit
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
            ->willReturn(false); // <------ failed

        // getErrors will be called after the fail, return some/any array
        $article
            ->method('getErrors')
            ->willReturn( array('example' => 'Bad example') );

        // toArray will be called when we forward to edit action
        // we can simply return $this->getArticleData()
        $article
            ->method('toArray')
            ->willReturn( $this->getArticleData() );


        // =================================
        // dispatch

        $this->put('/admin/articles/99', $this->getArticleData());


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
    }


    // submit

    public function testSubmitActionRedirectsToShowWhenSaveSuccess()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Mock the model the dependency
        $this->articleStub
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->with(array(
                'id' => 99,
            ))
            ->willReturn($article);

        // We want to confirm that status is set to approved
        $article
            ->expects( $this->once() )
            ->method('set')
            ->with( 'status', Article::STATUS_SUBMITTED );

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with( $this->getArticleData() )
            ->willReturn(true); // <------ success


        // =================================
        // dispatch

        $this->put('/admin/articles/99/submit', $this->getArticleData());


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('submit');
        $this->assertRedirectsTo('/admin/articles/99');

        // as this is a redirect, we haven't flushed the messages yet. another
        // thing we can test :)
        $this->assertTrue( $this->container['flash']->has('success') );
    }

    public function testSubmitActionRedirectsToEditWhenSaveFails()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Mock the model the dependency
        $this->articleStub
            ->expects( $this->exactly(2) ) // in approve, and again in edit
            ->method('findOneOrFail')
            ->with(array(
                'id' => 99,
            ))
            ->willReturn($article);

        // We want to confirm that status is set to approved
        $article
            ->expects( $this->once() )
            ->method('set')
            ->with( 'status', Article::STATUS_SUBMITTED );

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with( $this->getArticleData() )
            ->willReturn(false); // <------ failed

        // getErrors will be called after the fail, return some/any array
        $article
            ->method('getErrors')
            ->willReturn( array('example' => 'Bad example') );

        // toArray will be called when we forward to edit action
        // we can simply return $this->getArticleData()
        $article
            ->method('toArray')
            ->willReturn( $this->getArticleData() );


        // =================================
        // dispatch

        $this->put('/admin/articles/99/submit', $this->getArticleData());


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
    }


    // approve

    public function testApproveActionRedirectsToShowWhenSaveSuccess()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Mock the model the dependency
        $this->articleStub
            ->expects( $this->once() )
            ->method('findOneOrFail')
            ->with(array(
                'id' => 99,
            ))
            ->willReturn($article);

        // We want to confirm that status is set to approved
        $article
            ->expects( $this->once() )
            ->method('set')
            ->with( 'status', Article::STATUS_APPROVED );

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with( $this->getArticleData() )
            ->willReturn(true); // <------ success


        // =================================
        // dispatch

        $this->put('/admin/articles/99/approve', $this->getArticleData());


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('approve');
        $this->assertRedirectsTo('/admin/articles/99');

        // as this is a redirect, we haven't flushed the messages yet. another
        // thing we can test :)
        $this->assertTrue( $this->container['flash']->has('success') );
    }

    public function testApproveActionRedirectsToEditWhenSaveFails()
    {
        // we need to mock $article here because it will call it's save() and
        // then store in the database.. we'd rather not hit the db where possible
        $article = $this->generateArticleStub();


        // =================================
        // mock method stack, in order

        // Mock the model the dependency
        $this->articleStub
            ->expects( $this->exactly(2) ) // in approve, and again in edit
            ->method('findOneOrFail')
            ->with(array(
                'id' => 99,
            ))
            ->willReturn($article);

        // We want to confirm that status is set to approved
        $article
            ->expects( $this->once() )
            ->method('set')
            ->with( 'status', Article::STATUS_APPROVED );

        // Mock the response from save of $article
        $article
            ->expects( $this->once() )
            ->method('save')
            ->with( $this->getArticleData() )
            ->willReturn(false); // <------ failed

        // getErrors will be called after the fail, return some/any array
        $article
            ->method('getErrors')
            ->willReturn( array('example' => 'Bad example') );

        // toArray will be called when we forward to edit action
        // we can simply return $this->getArticleData()
        $article
            ->method('toArray')
            ->willReturn( $this->getArticleData() );


        // =================================
        // dispatch

        $this->put('/admin/articles/99/approve', $this->getArticleData());


        // =================================
        // assertions

        $this->assertController('articles');
        $this->assertAction('edit');
    }


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
