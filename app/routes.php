<?php
// Routes

// Define app routes
$app->get('/', 'CrSrc\Controller\IndexController#home');
$app->get('/contact', 'CrSrc\Controller\IndexController#contact');

// create resource method for Slim::resource($route, $name)
$app->resource('/articles', 'CrSrc\Controller\ArticlesController');

$app->resource('/admin/articles', 'CrSrc\Controller\Admin\ArticlesController');
