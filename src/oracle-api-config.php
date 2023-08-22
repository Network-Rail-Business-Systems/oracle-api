<?php

return [
    'enabled' => env('ORACLE_CATALOGUE_EMULATOR', false),

    'get_url' => env(
        'ORACLE_CATALOGUE_GET_HOST',
        '',
    ),

    'post_url' => env(
        'ORACLE_CATALOGUE_POST_HOST',
        ''
    ),

    'username' => env('ORACLE_CATALOGUE_USERNAME', 'RCOS'),

    'password' => env('ORACLE_CATALOGUE_PASSWORD', ''),
];
