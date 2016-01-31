<?php
namespace Tests\Models;

use Wordup\Model\Article;
use Wordup\Model\User;
use MartynBiz\Mongo\Connection;
use MartynBiz\Mongo\MongoIterator;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MartynBiz\Mongo\Connection_mock
     */
    protected $connection;

    public function setUp()
    {
        // unless we call parent::setUp() then we can skip the fixture
        // building stuff which isn't really neccessary here

        $this->connectionMock = $this->getMockBuilder('MartynBiz\\Mongo\\Connection')
			->disableOriginalConstructor()
			->getMock();

		// mock method to return mock collection
		$this->connectionMock
		 	->method('getNextSequence')
			->willReturn(1);

		// swap the instance as it's a singleton
		Connection::setInstance($this->connectionMock);
    }

    public function tearDown()
    {
        // reset Connection as it's being used across multiple tests (unit, int)
        Connection::getInstance()->resetInstance();
    }

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
            'photos' => 'photos',

            // not whitelisted - e.g. status
            'status' => 2,
            'type' => 'ARTICLE',
            'author' => 'Martyn',
        );

        $article = new Article($values);

        $this->assertEquals($values['title'], $article->title);
        $this->assertEquals($values['slug'], $article->slug);
        $this->assertEquals($values['photos'], $article->photos);

        $this->assertNotEquals($values['status'], @$article->status);
        $this->assertNotEquals($values['type'], @$article->type);
        $this->assertNotEquals($values['author'], @$article->author);
    }

    // public function testGetArticlesManagedByWithQueriesByUser()
    // {
    //     $model = (new Article());
    //
    //     $user = new User();
    //     $user->_id = new \MongoId();
    //
    //     $returns = array(
    //         array('title' => 'Have a nice day'),
    //     );
    //
    //     // mock the find method of Connection and assert that correct query was
    //     // used
    //     $this->connectionMock
    //         ->expects( $this->once() )
    //         ->method('find')
    //         ->with('articles', array(
    //             'author' => $user->getDBRef(),
    //         ), array())
	// 		->willReturn($returns);
    //
    //     $articles = $model->findArticlesOf($user);
    //
    //     $this->assertEquals($returns[0]['title'], $articles[0]->title);
    // }
}
