<?php

// default settings
$settings = [
    'settings' => [

        // Renderer settings
        'renderer' => [
            'template_path' => APPLICATION_PATH . '/views/',
        ],

        // photos upload dir
        'photos_dir' => APPLICATION_PATH . '/../data/photos/',

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => APPLICATION_PATH . '/../data/logs/app.log',
        ],
        'view' => [
            // 'cache' => realpath(APPLICATION_PATH . '/../cache/')
        ],
    ],

    // TODO move mongo inside settings maybe?
    'mongo' => [
        'db' => 'crsrc',
        'classmap' => [
            'articles' => '\\Wordup\\Model\\Article',
            'users' => '\\Wordup\\Model\\User',
        ]
    ],
    'mongo_testing' => [
        'db' => 'crsrc_testing',
        'classmap' => [
            'articles' => '\\Wordup\\Model\\Article',
            'users' => '\\Wordup\\Model\\User',
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
