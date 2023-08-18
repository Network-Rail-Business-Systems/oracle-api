<?php

return [
    'enabled' => env('ORACLE_CATALOGUE_EMULATOR', false),

    'get_url' => env(
        'ORACLE_CATALOGUE_GET_HOST',
        'http://10.57.8.8:8080/StoreCatalogV1/StoreCatalogRS',
    ),

    'post_url' => env(
        'ORACLE_CATALOGUE_POST_HOST',
        'http://10.57.8.8:8080/SalesOrderV1/SalesOrderRS/salesorders/RCOS/'
    ),

    'username' => env('ORACLE_CATALOGUE_USERNAME', 'RCOS'),

    'password' => env('ORACLE_CATALOGUE_PASSWORD', ''),
];
