<?php

namespace SlimMvc\Test\PHPUnit;

use SlimMvc\App;

use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Slim\App $app
     */
    protected $app;

    /**
     * Perform get dispatch
     */
    public function get($path)
    {
        $this->dispatch($path, 'GET');
    }

    /**
     * Perform post dispatch
     */
    public function post($path, $data=array())
    {
        $this->dispatch($path, 'POST', $data);
    }

    protected function dispatch($path, $method='GET', $data=array())
    {
        // Run app
        $this->app = App::getInstance();

        // Prepare a mock environment
        $env = Environment::mock(array(
            'REQUEST_URI' => $path,
            'REQUEST_METHOD' => $method,
        ));

        // Prepare request and response objects
        $uri = Uri::createFromEnvironment($env);
        $headers = Headers::createFromEnvironment($env);
        $cookies = [];
        $serverParams = $env->all();
        $body = new RequestBody();
        $req = new Request($method, $uri, $headers, $cookies, $serverParams, $body);
        $res = new Response();

        $response = call_user_func_array($this->app, array($req, $res));
    }

    public function assertController($controller)
    {
        $this->assertEquals($controller, $this->app->getControllerName());
    }

    public function assertAction($action)
    {
        $this->assertEquals($action, $this->app->getActionName());
    }

    public function assertStatusCode($statusCode)
    {
        $this->assertEquals($statusCode, $this->app->getStatusCode());
    }

    public function assertRedirects()
    {
        $statusCode = $this->app->getStatusCode();

        $this->assertTrue($statusCode >= 300 and $statusCode < 400);
    }

    public function assertRedirectsTo($path)
    {
        $statusCode = $this->app->getStatusCode();

        $this->assertTrue($statusCode >= 300 and $statusCode < 400);
    }
}
