<?php

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new \Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

// Register component on container
$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];
    $view = new \Slim\Views\Twig( APPLICATION_PATH . '/views/', $settings);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

// replace request with our own
$container['request'] = function ($c) {
    return \MartynBiz\Slim3Controller\Http\Request::createFromEnvironment($c->get('environment'));
};

// replace reponse with our own
$container['response'] = function ($c) {
    $headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new \MartynBiz\Slim3Controller\Http\Response(200, $headers);

    return $response->withProtocolVersion($c->get('settings')['httpVersion']);
};

$container['auth'] = function ($c) {

    // we're using Zend's AuthenticationService here
    $authService = new \Zend\Authentication\AuthenticationService();

    // even though SessionStorage is the default container, we want it to use
    // this app's object and namespace
    $authService->setStorage( new \Zend\Authentication\Storage\Session('crsrc') );

    // create an instance of our AuthInterface implemented class
    $auth = new \App\Auth($authService);

    return $auth;
};

$container['flash'] = function ($c) {
    $storage = new \Zend\Session\Container('crsrc_flash_messages');
    return new \MartynBiz\FlashMessage\Flash($storage);
};

$container['cache'] = function ($c) {

    // we wanna set the prefix so not to clash with other apps
    $backend = new \Predis\Client(null, array(
        'prefix' => 'crsrc:',
    ));

    $adapter = new \Desarrolla2\Cache\Adapter\Predis($backend);

    return new \Desarrolla2\Cache\Cache($adapter);
};


// Models

$container['model.article'] = function ($c) {
    return new \App\Model\Article();
};

$container['model.user'] = function ($c) {
    return new \App\Model\User();
};
