<?php
// Routes

// index routes (homepage, about, etc)
$app->group('', function () use ($app) {

    $controller = new CrSrc\Controller\IndexController($app);

    $app->get('/', $controller('index'));
    $app->get('/contact', $controller('contact'));
});

// index routes (homepage, about, etc)
$app->group('/session', function () use ($app) {

    $controller = new CrSrc\Controller\SessionController($app);

    $app->get('/login', $controller('login'));
    $app->post('', $controller('post'));
    $app->delete('/logout', $controller('logout'));
});

// create resource method for Slim::resource($route, $name)
$app->group('/articles', function () use ($app) {

    $controller = new CrSrc\Controller\ArticlesController($app);

    $app->get('/{id:[0-9]+}', $controller('show'));
    $app->get('/{id:[0-9]+}/{slug}', $controller('show'));
});

// users routes (eg. register)
$app->group('/users', function () use ($app) {

    $controller = new CrSrc\Controller\UsersController($app);

    $app->get('/create', $controller('create'));
    $app->post('', $controller('post'));
});

// admin routes
$app->group('/admin', function () use ($app) {

    // admin/articles routes
    $app->group('/articles', function () use ($app) {

        $controller = new CrSrc\Controller\Admin\ArticlesController($app);

        $app->get('', $controller('index'));
        $app->get('/{id:[0-9]+}', $controller('show'));
        $app->get('/create', $controller('create'));
        $app->get('/{id:[0-9]+}/edit', $controller('edit'));

        $app->post('', $controller('post'));
        $app->put('/{id:[0-9]+}', $controller('update'));
        $app->delete('/{id:[0-9]+}', $controller('delete'));
    });
})->add( new \CrSrc\Middleware\Auth() );
