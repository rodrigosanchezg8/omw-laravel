<?php

return [
    /*User's stuff*/
    'roles' => [
        'admin' => 'admin',
        'delivery_man' => 'delivery_man',
        'company' => 'company',
        'client' => 'client',
    ],

    /*Deliverie's stuff*/
    'delivery_statuses' => [
        'making' => 'Creando',
        'not_assigned' => 'No Asignado',
        'not_started' => 'No Iniciado',
        'in_progress' => 'En Progreso',
        'finished' => 'Entregado',
        'cancelled' => 'Cancelado',
    ],

    'origin_types' => [
        'sender' => 'sender' ,
        'receiver' => 'receiver',
    ],

    //Extra Arrival time for deliveries in days
    'default_extra_arrival_time' => 1,

    /*Delivery Men's stuff*/
    'delivery_man_statuses' => [
        'available' => 1,
        'bussy' => 0,
    ],

    //Available distances for delivery men in kilometers
    'distances' => [
        'local' => 50,
        'short' => 125,
        'medium' => 250,
        'medium_large' => 500,
        'large' => 700,
        'too_large' => 1000,
    ],

    'min_delivery_man_distance_from_origin' => 15,
];
