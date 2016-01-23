<?php

// default settings
return [
    'settings' => [
        'view' => [
            'cache' => realpath(APPLICATION_PATH . '/../cache/')
        ],
    ],
    'mongo' => [
        'db' => 'wordup',
        // 'username' => 'myuser',
        // 'password' => 'mypass',
    ],
];
