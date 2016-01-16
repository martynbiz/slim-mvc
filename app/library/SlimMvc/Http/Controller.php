<?php
namespace SlimMvc\Http;

abstract class Controller
{
    // Optional properties
    protected $app;
    protected $request;
    protected $response;

    /**
     * @param \Slim\App $app
     */
    public function __construct(\Slim\App $app)
    {
        $this->app = $app;
    }

    /**
     * This method allows use to return a callable that calls the action for
     * the route.
     * @param string $action Name of the action method to call
     */
    public function __invoke($actionName)
    {
        $app = $this->app;
        $controller = $this;

        $callable = function ($request, $response, $args) use ($app, $controller, $actionName) {

            $container = $app->getContainer();

            // Set the app, request and response into the controller if we can
            if (method_exists($controller, 'setApp')) {
                $controller->setApp($app);
            }
            if (method_exists($controller, 'setRequest')) {
                $controller->setRequest($request);
            }
            if (method_exists($controller, 'setResponse')) {
                $controller->setResponse($response);
            }

            // store the name of the controller and action so we can assert during tests
            $controllerName = get_class($controller); // eg. CrSrc\Controller\Admin\ArticlesController
            $controllerName = strtolower($controllerName); // eg. crsrc\controller\admin\articlescontroller
            $controllerName = rtrim($controllerName, 'controller'); // eg. crsrc\controller\admin\articles
            $controllerName = array_pop(explode('\\', $controllerName)); // eg. articles

            // these values will be useful when testing, but not included with the
            // Slim\Http\Response. Instead use SlimMvc\Http\Response
            if (method_exists($response, 'setControllerName')) {
                $response->setControllerName($controllerName);
            }
            if (method_exists($response, 'setActionName')) {
                $response->setActionName($actionName);
            }

            return call_user_func_array(array($controller, $actionName), $args);
        };

        return $callable;
    }

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
        if (method_exists($this->response, 'setActionName')) {
            $this->response->setActionName($actionName);
        }

        return call_user_func_array(array($this, $actionName), $data);
    }
}
