<?php
// DIC configuration

use Slim\Http\Headers;

use App\Http\Request;
use App\Http\Response;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
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

// TODO move to App\Slim::run() -- won't affect tests
// replace request with our own
$container['request'] = function ($container) {
    return Request::createFromEnvironment($container->get('environment'));
};

// replace reponse with our own
$container['response'] = function ($container) {
    $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
    $response = new Response(200, $headers);

    return $response->withProtocolVersion($container->get('settings')['httpVersion']);
};

// Models

$container['model.article'] = function ($container) {
    return new App\Model\Article();
};
