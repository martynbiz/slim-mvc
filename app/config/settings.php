<?php
return [
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
