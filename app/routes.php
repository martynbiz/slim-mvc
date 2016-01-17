<?php
// Routes

// index routes (homepage, about, etc)
$app->group('', function () use ($app) {

    $controller = new CrSrc\Controller\IndexController($app);

    $app->get('/', $controller('index'))->setName('index');
    $app->get('/contact', $controller('contact'))->setName('contact');
});

// index routes (homepage, about, etc)
$app->group('/session', function () use ($app) {

    $controller = new CrSrc\Controller\SessionController($app);

    $app->get('/login', $controller('login'))->setName('session_login');
    $app->post('', $controller('post'))->setName('session_post');
    $app->delete('', $controller('delete'))->setName('session_delete');
});

// create resource method for Slim::resource($route, $name)
$app->group('/articles', function () use ($app) {

    $controller = new CrSrc\Controller\ArticlesController($app);

    $app->get('/{id:[0-9]+}', $controller('show'))->setName('articles_show');
    $app->get('/{id:[0-9]+}/{slug}', $controller('show'))->setName('articles_show_wslug');
});

// users routes (eg. register)
$app->group('/users', function () use ($app) {

    $controller = new CrSrc\Controller\UsersController($app);

    $app->get('/create', $controller('create'))->setName('users_create');
    $app->post('', $controller('post'))->setName('users_post');
});

// admin routes -- invokes auth middleware
$app->group('/admin', function () use ($app) {

    // admin/articles routes
    $app->group('/articles', function () use ($app) {

        $controller = new CrSrc\Controller\Admin\ArticlesController($app);
        $container = $app->getContainer();

        $app->get('', $controller('index'))->setName('admin_articles_index');
        $app->get('/{id:[0-9]+}', $controller('show'))->setName('admin_articles_show');
        $app->get('/create', $controller('create'))->setName('admin_articles_create');
        $app->get('/{id:[0-9]+}/edit', $controller('edit'))->setName('admin_articles_edit');

        $app->post('', $controller('post'))->setName('admin_articles_post');
        $app->put('/{id:[0-9]+}', $controller('update'))->setName('admin_articles_update');
        $app->delete('/{id:[0-9]+}', $controller('delete'))->setName('admin_articles_delete');
    });
})->add( new \CrSrc\Middleware\Auth() );
