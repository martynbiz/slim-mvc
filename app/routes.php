<?php
// Routes

// Define app routes
$app->get('/', 'CrSrc\Controller\IndexController#home');
$app->get('/contact', 'CrSrc\Controller\IndexController#contact');

// create resource method for Slim::resource($route, $name)
$app->resource('/articles', 'CrSrc\Controller\ArticlesController');

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
});
