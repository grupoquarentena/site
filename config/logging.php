<?php

use Monolog\Handler\StreamHandler;

return [
    'default' => env('LOG_CHANNEL', 'stderr'),
    'channels' => [
        'stderr' => ['driver' => 'monolog', 'level' => env('LOG_LEVEL', 'warning'), 'handler' => StreamHandler::class, 'with' => ['stream' => 'php://stderr']],
        'single' => ['driver' => 'single', 'path' => storage_path('logs/laravel.log'), 'level' => env('LOG_LEVEL', 'warning')],
        'stack' => ['driver' => 'stack', 'channels' => ['stderr'], 'ignore_exceptions' => false],
    ],
];
