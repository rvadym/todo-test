<?php

define('APP_ROOT', __DIR__ . '/');

return [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,

        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => true,

            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => APP_ROOT . '/var/doctrine',

            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [APP_ROOT . 'src/Adapters/Doctrine/Entity'],

            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => 'todotest_db',
                'port' => 3306,
                'dbname' => 'todotest',
                'user' => 'root',
                'password' => 'example',
                'charset' => 'utf8'
            ]
        ]
    ]
];
