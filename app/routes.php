<?php
// Routes

// Define app routes
$app->get('/', 'App\Controller\IndexController#home');
$app->get('/contact', 'App\Controller\IndexController#contact');

// create resource method for Slim::resource($route, $name)
$app->resource('/articles', 'App\Controller\ArticlesController');

$app->resource('/admin/articles', 'App\Controller\Admin\ArticlesController');
