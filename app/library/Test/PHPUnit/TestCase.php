<?php

namespace App\Test\PHPUnit;

use MartynBiz\Mongo\Connection;
use Zend\Authentication\Result;

use App\Model\User;
use App\Model\Article;

abstract class TestCase extends \MartynBiz\Slim3Controller\Test\PHPUnit\TestCase
{
    /**
     * @var Slim\Container
     */
    protected $container;

    public function setUp()
    {
        // =========================
        // Instantiate the app
        $settings = require APPLICATION_PATH . '/config/global.php';
        $app = new \Slim\App($settings);

        // Set up dependencies
        require APPLICATION_PATH . '/dependencies.php';

        // Register middleware
        require APPLICATION_PATH . '/middleware.php';

        // Register routes
        require APPLICATION_PATH . '/routes.php';

        $this->app = $app;


        // =========================
        // Init mongo

        Connection::getInstance()->init($settings['mongo_testing']);


        // =========================
        // create fixtures

        $this->user = new User( array(
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martyn@example.com',
            'password' => 'mypass',
        ) );

        $this->user->save();

        $this->article = new Article( array(
            'title' => 'A long time ago in a galaxy far far away...',
            'description' => '...',
            'author' => $this->user->getDBRef(),
        ) );

        $this->article->save();


        // =========================
        // Create test stubs

        $this->container = $this->app->getContainer();

        //  auth service
        $authMock = $this->getMockBuilder('App\\Auth\\Auth')
            ->disableOriginalConstructor()
            ->getMock();
        $this->container['auth'] = $authMock;
    }

    public function login()
    {
        // by defaut, we'll make getIdentity return a null
        $this->container['auth']
            ->method('getIdentity')
            ->willReturn( $this->user->email );

        // by defaut, we'll make isAuthenticated return a false
        $this->container['auth']
            ->method('isAuthenticated')
            ->willReturn( true );
    }

    /**
     * Will generate an article stub for use in this test. Sometimes we want to
     * mock methods of the model instance such as save, and ensure values
     * are being set etc
     * @return Article_mock
     */
    public function generateArticleStub()
    {
        return $this->getMockBuilder('App\Model\Article')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Will generate an user stub for use in this test.
     * @return User_mock
     */
    public function generateUserStub()
    {
        return $this->getMockBuilder('App\Model\User')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
