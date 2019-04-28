<?php

return [
    'roles' => [
        'admin' => 'admin',
        'delivery_man' => 'delivery_man',
        'company' => 'company',
        'client' => 'client',
    ],

    'delivery_statuses' => [
        'making' => 'Creando',
        'not_assigned' => 'No asignado',
        'not_started' => 'No Iniciado',
        'in_progress' => 'En Progreso',
        'finished' => 'Entregado',
        'cancelled' => 'Cancelado',
    ],

    /*Available distances for delivery men in kilometers*/
    'distances' => [
        'local' => 50,
        'short' => 125,
        'medium' => 250,
        'medium_large' => 500,
        'large' => 700,
        'too_large' => 1000,
    ],

    'delivery_man_statuses' => [
        'available' => 1,
        'bussy' => 0,
    ],

    'min_delivery_man_distance_from_origin' => 15,

    'origin_types' => [
        'sender' => 'sender' ,
        'receiver' => 'receiver',
    ]
];
