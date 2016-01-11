<?php
namespace App\Controller;

class SlimController
{
    // Optional properties
    protected $app;
    protected $request;
    protected $response;

    // Optional setters
    public function setApp($app)
    {
        $this->app = $app;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    protected function render($file, $args=array())
    {
        $container = $this->app->getContainer();

        // return $container->renderer->render($this->response, $file, $args);
        return $container->view->render($this->response, $file, $args);
    }
}
