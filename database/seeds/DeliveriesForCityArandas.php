<?php

use App\Delivery;
use Illuminate\Database\Seeder;

class DeliveriesForCityArandas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $num = mt_rand(1, 50);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-06-14 02:11:52',
                'planned_end_date' => '2018-06-14 02:11:52',
                'arrival_date' => '2018-06-14 02:11:52',
                'created_at' => '2018-06-14 02:11:52',
                'updated_at' => '2018-06-14 02:11:52',
            ]);

        }

        $num = mt_rand(1, 50);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-07-14 02:11:52',
                'planned_end_date' => '2018-07-14 02:11:52',
                'arrival_date' => '2018-07-14 02:11:52',
                'created_at' => '2018-07-14 02:11:52',
                'updated_at' => '2018-07-14 02:11:52',
            ]);

        }

        $num = mt_rand(1, 50);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-08-14 02:11:52',
                'planned_end_date' => '2018-08-14 02:11:52',
                'arrival_date' => '2018-08-14 02:11:52',
                'created_at' => '2018-08-14 02:11:52',
                'updated_at' => '2018-08-14 02:11:52',
            ]);

        }

        $num = mt_rand(50, 100);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-09-14 02:11:52',
                'planned_end_date' => '2018-09-14 02:11:52',
                'arrival_date' => '2018-09-14 02:11:52',
                'created_at' => '2018-09-14 02:11:52',
                'updated_at' => '2018-09-14 02:11:52',
            ]);

        }

        $num = mt_rand(50, 100);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-10-14 02:11:52',
                'planned_end_date' => '2018-10-14 02:11:52',
                'arrival_date' => '2018-10-14 02:11:52',
                'created_at' => '2018-10-14 02:11:52',
                'updated_at' => '2018-10-14 02:11:52',
            ]);

        }

        $num = mt_rand(50, 100);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-11-14 02:11:52',
                'planned_end_date' => '2018-11-14 02:11:52',
                'arrival_date' => '2018-11-14 02:11:52',
                'created_at' => '2018-11-14 02:11:52',
                'updated_at' => '2018-11-14 02:11:52',
            ]);

        }

        $num = mt_rand(100, 150);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2018-12-14 02:11:52',
                'planned_end_date' => '2018-12-14 02:11:52',
                'arrival_date' => '2018-12-14 02:11:52',
                'created_at' => '2018-12-14 02:11:52',
                'updated_at' => '2018-12-14 02:11:52',
            ]);

        }

        $num = mt_rand(100, 150);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2019-01-14 02:11:52',
                'planned_end_date' => '2019-01-14 02:11:52',
                'arrival_date' => '2019-01-14 02:11:52',
                'created_at' => '2019-01-14 02:11:52',
                'updated_at' => '2019-01-14 02:11:52',
            ]);

        }

        $num = mt_rand(100, 150);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2019-02-14 02:11:52',
                'planned_end_date' => '2019-02-14 02:11:52',
                'arrival_date' => '2019-02-14 02:11:52',
                'created_at' => '2019-02-14 02:11:52',
                'updated_at' => '2019-02-14 02:11:52',
            ]);

        }

        $num = mt_rand(150, 200);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2019-03-14 02:11:52',
                'planned_end_date' => '2019-03-14 02:11:52',
                'arrival_date' => '2019-03-14 02:11:52',
                'created_at' => '2019-03-14 02:11:52',
                'updated_at' => '2019-03-14 02:11:52',
            ]);

        }

        $num = mt_rand(150, 200);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2019-04-14 02:11:52',
                'planned_end_date' => '2019-04-14 02:11:52',
                'arrival_date' => '2019-04-14 02:11:52',
                'created_at' => '2019-04-14 02:11:52',
                'updated_at' => '2019-04-14 02:11:52',
            ]);

        }

        $num = mt_rand(150, 200);

        for ($i = 0; $i < $num; $i++) {

            Delivery::create([
                'sender_id' => 2,
                'receiver_id' => 16,
                'delivery_man_id' => 10,
                'company_is_sending' => 0,
                'delivery_status_id' => 5,
                'planned_start_date' => '2019-05-14 02:11:52',
                'planned_end_date' => '2019-05-14 02:11:52',
                'arrival_date' => '2019-05-14 02:11:52',
                'created_at' => '2019-05-14 02:11:52',
                'updated_at' => '2019-05-14 02:11:52',
            ]);

        }
    }
}
