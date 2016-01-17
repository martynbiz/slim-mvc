<?php

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($container) {
    $settings = $c->get('settings')['renderer'];
    return new \Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($container) {
    $settings = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

// Register component on container
$container['view'] = function ($container) {
    // TODO switch on caching for production
    $view = new \Slim\Views\Twig( APPLICATION_PATH . '/views/', [
        // 'cache' => realpath(APPLICATION_PATH . '/../cache/')
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

// replace request with our own
$container['request'] = function ($container) {
    return \SlimMvc\Http\Request::createFromEnvironment($container->get('environment'));
};

// replace reponse with our own
$container['response'] = function ($container) {
    $headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new \SlimMvc\Http\Response(200, $headers);

    return $response->withProtocolVersion($container->get('settings')['httpVersion']);
};

$container['auth'] = function ($container) {

    // we're using Zend's AuthenticationService here
    $authService = new \Zend\Authentication\AuthenticationService();

    // even though SessionStorage is the default container, we want it to use
    // this app's object and namespace
    $authService->setStorage( new \Zend\Authentication\Storage\Session('crsrc') );

    // create an instance of our AuthInterface implemented class
    $auth = new \CrSrc\Auth($authService);

    return $auth;
};

$container['flash'] = function ($container) {
    // TODO get this to work :(
    $storage = null; //new \Zend\Session\Container('crsrc_flash_messages');
    return new \MartynBiz\Flash($storage);
};

$container['cache'] = function ($container) {

    // we wanna set the prefix so not to clash with other apps
    $backend = new \Predis\Client(null, array(
        'prefix' => 'crsrc:',
    ));

    $adapter = new \Desarrolla2\Cache\Adapter\Predis($backend);

    return new \Desarrolla2\Cache\Cache($adapter);
};


// Models

$container['model.article'] = function ($container) {
    return new \CrSrc\Model\Article();
};

$container['model.user'] = function ($container) {
    return new \CrSrc\Model\User();
};
