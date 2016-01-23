<?php
// Routes

$container = $app->getContainer();

// index routes (homepage, about, etc)
$app->group('', function () use ($app) {

    $controller = new Wordup\Controller\IndexController($app);

    $app->get('/', $controller('index'))->setName('index_index');
    $app->get('/contact', $controller('contact'))->setName('index_contact');
});

// index routes (homepage, about, etc)
$app->group('/session', function () use ($app) {

    $controller = new Wordup\Controller\SessionController($app);

    $app->get('/login', $controller('login'))->setName('session_login');
    $app->post('', $controller('post'))->setName('session_post');
    $app->delete('', $controller('delete'))->setName('session_delete');
});

// create resource method for Slim::resource($route, $name)
$app->group('/articles', function () use ($app) {

    $controller = new Wordup\Controller\ArticlesController($app);

    $app->get('/{id:[0-9]+}', $controller('show'))->setName('articles_show');
    $app->get('/{id:[0-9]+}/{slug}', $controller('show'))->setName('articles_show_wslug');
});

// users routes (eg. register)
$app->group('/users', function () use ($app) {

    $controller = new Wordup\Controller\UsersController($app);

    $app->get('/create', $controller('create'))->setName('users_create');
    $app->post('', $controller('post'))->setName('users_post');
});

// admin routes -- invokes auth middleware
$app->group('/admin', function () use ($app) {

    // admin/articles routes
    $app->group('/articles', function () use ($app) {

        $controller = new Wordup\Controller\Admin\ArticlesController($app);

        $app->get('', $controller('index'))->setName('admin_articles_index');
        $app->get('/{id:[0-9]+}', $controller('show'))->setName('admin_articles_show');
        $app->get('/create', $controller('create'))->setName('admin_articles_create');
        $app->get('/{id:[0-9]+}/edit', $controller('edit'))->setName('admin_articles_edit');

        $app->post('', $controller('post'))->setName('admin_articles_post');
        $app->put('/{id:[0-9]+}', $controller('update'))->setName('admin_articles_update');
        $app->delete('/{id:[0-9]+}', $controller('delete'))->setName('admin_articles_delete');

        $app->put('/{id:[0-9]+}/submit', $controller('submit'))->setName('admin_articles_submit');
        $app->put('/{id:[0-9]+}/approve', $controller('approve'))->setName('admin_articles_approve');
    });

    // admin/users routes
    $app->group('/users', function () use ($app) {

        $controller = new Wordup\Controller\Admin\UsersController($app);

        $app->get('', $controller('index'))->setName('admin_users_index');
        $app->get('/{id:[0-9]+}/edit', $controller('edit'))->setName('admin_users_edit');

        $app->put('/{id:[0-9]+}', $controller('update'))->setName('admin_users_update');
        $app->delete('/{id:[0-9]+}', $controller('delete'))->setName('admin_users_delete');
    });
})->add( new \Wordup\Middleware\Auth( $container['auth'] ) );
