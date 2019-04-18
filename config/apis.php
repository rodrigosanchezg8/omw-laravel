<?php

return [
    'mapquest' => [
        'customer_key' => env('PROJECT_KEY_FOR_MAPQUEST', ''),
        'customer_secret_key' => env('PROJECT_SECRET_KEY_FOR_MAPQUEST', ''),
        'mapquest_distance_matrix_url' => env('MAPQUEST_DISTANCEMATRIX_URL', ''),
    ],
];
