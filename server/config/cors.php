<?php

return [

    'paths' => [

        'api/*',

        'sanctum/csrf-cookie',

        'sign-up',

        'sign-in',

        'recover-password',

        'reset-password',

        'me',

        'sign-out',

        'lists'

    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
