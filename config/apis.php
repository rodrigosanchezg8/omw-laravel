<?php

return [
    'mapquest' => [
        'customer_key' => env('PROJECT_KEY_FOR_MAPQUEST', ''),
        'customer_secret_key' => env('PROJECT_SECRET_KEY_FOR_MAPQUEST', ''),
        'base_url' => env('MAPQUEST_BASE_URL', ''),
        'distance_matrix_url' => env('MAPQUEST_DISTANCE_MATRIX_URL', ''),
    ],
];
