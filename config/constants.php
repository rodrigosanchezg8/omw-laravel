<?php

return [
    'roles' => [
        'admin' => 'admin',
        'delivery_man' => 'delivery_man',
        'company' => 'company',
        'client' => 'client',
    ],

    'delivery_statuses' => [
        'not_started' => 'No Iniciado',
        'in_progress' => 'En Progreso',
        'finished' => 'Entregado',
        'cancelled' => 'Cancelado',
    ],

    'distances' => [
        'short' => 50,
        'medium' => 125,
        'long' => 250
    ],

    'delivery_man_statuses' => [
        'available' => 1,
        'bussy' => 0,
    ],

    'min_delivery_man_distance_from_origin' => '15',
];
