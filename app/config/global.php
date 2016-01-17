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
        'view' => [
            // 'cache' => realpath(APPLICATION_PATH . '/../cache/')
        ],
    ],
    'mongo' => [
        'db' => 'crsrc',
        'classmap' => [
            'articles' => '\\App\\Model\\Article',
            'users' => '\\App\\Model\\User',
        ]
    ],
    'mongo_testing' => [
        'db' => 'crsrc_testing',
        'classmap' => [
            'articles' => '\\App\\Model\\Article',
            'users' => '\\App\\Model\\User',
        ]
    ],
];

// load environment settings
if (file_exists(APPLICATION_PATH . '/config/' . APPLICATION_ENV . '.php')) {
    $settings = array_replace_recursive(
        $settings,
        require APPLICATION_PATH . '/config/' . APPLICATION_ENV . '.php'
    );
}

// load any private settings (eg. database credentials)
if (file_exists(APPLICATION_PATH . '/config/local.php')) {
    $settings = array_replace_recursive(
        $settings,
        require APPLICATION_PATH . '/config/local.php'
    );
}

return $settings;
