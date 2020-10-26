<?php

use Monolog\Handler\StreamHandler;

return [

    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver'   => 'stack',
            'channels' => ['stdout', 'app'],
        ],

        'stdout' => [
            'driver'  => 'monolog',
            'handler' => StreamHandler::class,
            'with'    => [
                'stream' => 'php://stdout',
            ],
        ],

        'app' => [
            'driver' => env('LOG_DRIVER', 'daily'),
            'path'   => storage_path('logs/app.log'),
            'level'  => env('LOG_LEVEL', 'debug'),
            'days'   => 7,
        ],
    ],
];
