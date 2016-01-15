<?php
namespace SlimMvc\Http;

abstract class Controller
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

    /**
     * Get the POST params
     */
    protected function getPost()
    {
        return $this->request->getParams();
    }

    /**
     * Shorthand method to get dependency from container
     */
    protected function get($name)
    {
        return $this->app->getContainer()->get($name);
    }

    /**
     * Redirect.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param  string|UriInterface $url    The redirect destination.
     * @param  int                 $status The redirect HTTP status code.
     * @return self
     */
    public function redirect($url, $status = 302)
    {
        return $this->response->withRedirect($url, $status);
    }

    /**
     * Pass on the control to another action. Of the same class (for now)
     *
     * @param  string $actionName    The redirect destination.
     * @param  string                 $status The redirect HTTP status code.
     * @return self
     */
    public function forward($actionName, $data=array())
    {
        // update the action name that was last used
        $this->response->setActionName($actionName);

        return call_user_func_array(array($this, $actionName), $data);
    }
}
