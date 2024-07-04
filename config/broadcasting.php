<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "redis", "log", "null"
    |
    */

    'default' => 'soketi',

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'soketi' => [
            'driver' => 'pusher',
            'key' => env('WEBSOCKET_APP_KEY', 'placeholder'),
            'secret' => env('WEBSOCKET_APP_SECRET', 'placeholder'),
            'app_id' => env('WEBSOCKET_APP_ID', 'placeholder'),
            'options' => [
                'host' => env('WEBSOCKET_HOST', 'websockets'),
                'port' => env('WEBSOCKET_PORT', 6001),
                'scheme' => env('WEBSOCKET_SCHEME', 'http'),
                'encrypted' => false,
                'useTLS' => false,
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
