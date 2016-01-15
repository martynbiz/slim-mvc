<?php

// default settings
$settings = [
    'settings' => [
        'displayErrorDetails' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => APPLICATION_PATH . '/views/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => APPLICATION_PATH . '/../logs/app.log',
        ],
    ],
];

// load environment settings
if (file_exists(APPLICATION_PATH . '/config/' . APPLICATION_ENV . '.php')) {
    $settings = array_merge_recursive(
        $settings,
        require APPLICATION_PATH . '/config/' . APPLICATION_ENV . '.php'
    );
}

// load any private settings (eg. database credentials)
if (file_exists(APPLICATION_PATH . '/config/local.php')) {
    $settings = array_merge_recursive(
        $settings,
        require APPLICATION_PATH . '/config/local.php'
    );
}

return $settings;
