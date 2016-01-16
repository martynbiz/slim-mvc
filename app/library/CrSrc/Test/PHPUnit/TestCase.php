<?php

namespace CrSrc\Test\PHPUnit;

use CrSrc\Model\Article;

use MartynBiz\Mongo\Connection;

class TestCase extends \SlimMvc\Test\PHPUnit\TestCase
{
    public function setUp()
    {
        // =========================
        // Instantiate the app
        $settings = require APPLICATION_PATH . '/config/settings.php';
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

        Connection::getInstance()->init($settings['mongo']['testing']);


        // =========================
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
}
