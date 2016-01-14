<?php

namespace SlimMvc\Test\PHPUnit;

use SlimMvc\App;

use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\RequestBody;
use Slim\Http\Uri;

use App\Http\Request;
use App\Http\Response;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SlimMvc\App
     */
    protected $app;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var Headers
     */
    protected $headers;

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

        // create request, and set params
        $req = new Request($method, $uri, $headers, $cookies, $serverParams, $body);
        if (!empty($data))
            $req = $req->withParsedBody($data);

        $res = new Response();

        $this->headers = $headers;
        $this->request = $req;
        $this->response = call_user_func_array($this->app, array($req, $res));
    }

    public function assertController($controllerName)
    {
        $this->assertEquals($controllerName, $this->response->getControllerName());
    }

    public function assertAction($actionName)
    {
        $this->assertEquals($actionName, $this->response->getActionName());
    }

    public function assertStatusCode($statusCode)
    {
        $this->assertEquals($statusCode, $this->response->getStatusCode());
    }

    public function assertRedirects()
    {
        $statusCode = $this->response->getStatusCode();

        $this->assertTrue($statusCode >= 300 and $statusCode < 400);
    }

    public function assertRedirectsTo($path)
    {

    }
}
