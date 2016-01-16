<?php
// Application middleware

$container = $app->getContainer();

// Register middleware for all routes
// If you are implementing per-route checks you must not add this
$app->add($container->get('auth'));

// // Register middleware for all routes
// // If you are implementing per-route checks you must not add this
// $app->add($container->get('csrf'));
