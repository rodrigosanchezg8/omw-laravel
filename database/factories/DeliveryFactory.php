<?php

use Faker\Generator as Faker;

$factory->define(App\Delivery::class, function (Faker $faker) {
    return [
        'sender_id' => 2,
        'receiver_id' => 3,
        'company_is_sending' => 0,
        'delivery_status_id' => 1,
        'created_at' => '2019-05-14 02:11:52',
        'updated_at' => '2019-05-14 02:11:52',
    ];
});
