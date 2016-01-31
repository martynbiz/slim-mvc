<?php

use MartynBiz\Mongo\Connection;
//
use Wordup\Model\User;
use Wordup\Model\Article;
use Wordup\Model\Tag;

abstract class TestCase extends \MartynBiz\Slim3Controller\Test\PHPUnit\TestCase
{
    /**
     * @var Slim\Container
     */
    protected $container;

    public function setUp()
    {
        // =========================
        // Instantiate the app and container
        $settings = require APPLICATION_PATH . '/config/global.php';
        $this->app = $app = new \Slim\App($settings);
        $this->container = $app->getContainer();


        // =========================
        // Set up dependencies
        require APPLICATION_PATH . '/dependencies.php';


        // =========================
        // Create test stubs
        // In some cases, where services have become "frozen", we need to define
        // mocks before they are loaded

        //  auth service
        $authMock = $this->getMockBuilder('Wordup\\Auth\\Auth')
            ->disableOriginalConstructor()
            ->getMock();
        $this->container['auth'] = $authMock;


        // =========================
        // Register middleware
        require APPLICATION_PATH . '/middleware.php';


        // =========================
        // Register routes
        require APPLICATION_PATH . '/routes.php';

        $this->app = $app;


        // =========================
        // Init mongo

        Connection::getInstance()->init($settings['mongo_testing']);


        // =========================
        // create fixtures

        $this->adminUser = new User( array(
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martyn@example.com',
            'password' => 'mypass',
        ) );
        $this->adminUser->role = User::ROLE_ADMIN;
        $this->adminUser->save();

        $this->editorUser = new User( array(
            'first_name' => 'Neil',
            'last_name' => 'McInness',
            'email' => 'neil@example.com',
            'password' => 'mypass',
        ) );
        $this->editorUser->role = User::ROLE_EDITOR;
        $this->editorUser->save();

        $this->ownerUser = new User( array(
            'first_name' => 'Louise',
            'last_name' => 'McInness',
            'email' => 'louise@example.com',
            'password' => 'mypass',
        ) );
        $this->ownerUser->role = User::ROLE_MEMBER;
        $this->ownerUser->save();

        $this->randomUser = new User( array(
            'first_name' => 'Moses',
            'last_name' => 'Cat',
            'email' => 'moses@example.com',
            'password' => 'mypass',
        ) );
        $this->randomUser->role = User::ROLE_MEMBER;
        $this->randomUser->save();

        $this->article = new Article( array(
            'title' => 'A long time ago in a galaxy far far away...',
            'description' => '...',
        ) );
        $this->article->author = $this->ownerUser;
        $this->article->save();

        $this->tag = new Tag( array(
            'name' => 'Travel',
            'slug' => 'travel',
        ) );
        $this->tag->save();
    }

    public function tearDown()
    {
        // clear fixtures
        User::remove(array());
        Article::remove(array());
    }

    public function login($user)
    {
        // return an identity (eg. email)
        $this->container['auth']
            ->method('getIdentity')
            ->willReturn( 'martyn@example.com' );

        // by defaut, we'll make isAuthenticated return a false
        $this->container['auth']
            ->method('isAuthenticated')
            ->willReturn( true );

        // by defaut, we'll make isAuthenticated return a false
        $this->container['auth']
            ->method('getCurrentUser')
            ->willReturn($user);
    }

    /**
     * Will generate an article stub for use in this test. Sometimes we want to
     * mock methods of the model instance such as save, and ensure values
     * are being set etc
     * @return Article_mock
     */
    public function generateArticleStub()
    {
        return $this->getMockBuilder('Wordup\Model\Article')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Will generate an user stub for use in this test.
     * @return User_mock
     */
    public function generateUserStub()
    {
        return $this->getMockBuilder('Wordup\Model\User')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
