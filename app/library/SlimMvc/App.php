<?php
/**
 * Override of \Slim\Slim to add the ability to dynamically create a controller
 * and call an action method on it.
 *
 * Copyright 2014-2015 Rob Allen (rob@akrabat.com).
 * License: New-BSD
 */
namespace SlimMvc;

use Slim\App as Slim;

class App extends Slim
{
    private static $instance;

    /**
     * Create new application - extended to store the instance which we'll use in tests
     *
     * @param ContainerInterface|array $container Either a ContainerInterface or an associative array of application settings
     * @throws InvalidArgumentException when no container is provided that implements ContainerInterface
     */
    public function __construct($container = [])
    {
        parent::__construct($container);

        // store the instance for getInstance() calls
        static::$instance = $this;
    }

    /**
     * Instance is only available AFTER class has been instantiated with new
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            throw new Exception('Class has not been instantiated.');
        }

        return static::$instance;
    }

    /**
     * Add route with multiple methods
     *
     * @param  string[] $methods  Numeric array of HTTP method names
     * @param  string   $pattern  The route URI pattern
     * @param  mixed    $callable The route callback routine
     *
     * @return RouteInterface
     */
    public function map(array $methods, $pattern, $callable)
    {
        $container = $this->getContainer();

        if ($callable instanceof Closure) {
            $callable = $callable->bindTo($container);
        } else if (is_string($callable) && substr_count($callable, '#', 1) == 1) {
            $callable = $this->createControllerClosure($callable);
        }

        $route = $container->get('router')->map($methods, $pattern, $callable);
        if (is_callable([$route, 'setContainer'])) {
            $route->setContainer($container);
        }

        if (is_callable([$route, 'setOutputBuffering'])) {
            $route->setOutputBuffering($container->get('settings')['outputBuffering']);
        }

        return $route;
    }

    /**
    * Add REST routes
    * @param  string $pattern  The route URI pattern
    * @param  string  $callable The controller class name
    * @return void
    */
    public function resource($pattern, $callable)
    {
        $this->get($pattern, $callable . '#index');
        $this->get($pattern . '/{id:[0-9]+}', $callable . '#show');
        $this->get($pattern . '/create', $callable . '#create');
        $this->get($pattern . '/{id:[0-9]+}/edit', $callable . '#edit');

        $this->post($pattern, $callable . '#post');
        $this->put($pattern . '/{id:[0-9]+}', $callable . '#update');
        $this->delete($pattern . '/{id:[0-9]+}', $callable . '#delete');
    }

    /**
     * Create a closure that instantiates (or gets from container) and then calls
     * the action method.
     *
     * Also if the methods exist on the controller class, call setApp(), setRequest()
     * and setResponse() passing in the appropriate object.
     *
     * @param  string $name controller class name and action method name separated by a colon
     * @return closure
     */
    protected function createControllerClosure($name)
    {
        list($controllerClassName, $actionName) = explode('#', $name);

        // Create a callable that will find or create the controller instance
        // and then execute the action
        $app = $this;
        $callable = function ($request, $response, $args) use ($app, $controllerClassName, $actionName) {

            $container = $app->getContainer();

            // Try to fetch the controller instance from Slim's container
            if ($container->has($controllerClassName)) {
                $controller = $container->get($controllerClassName);
            } else {
                // not in container, assume it can be directly instantiated
                $controller = new $controllerClassName($app);
            }

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

            // Call init in case the controller wants to do something now that
            // it has an app, request and response.
            if (method_exists($controller, 'init')) {
                $controller->init();
            }

            // store the name of the controller and action so we can assert during tests
            $controllerName = strtolower($controllerClassName);
            $controllerName = rtrim($controllerName, 'controller');
            $controllerName = array_pop(explode('\\', $controllerName));

            // these values will be useful when testing
            $response->setControllerName($controllerName);
            $response->setActionName($actionName);
            // $response->setStatusCode($response->getStatusCode());

            return call_user_func_array(array($controller, $actionName), $args);
        };

        return $callable;
    }
}
