<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(ServiceRangesTableSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(DeliveryStatusesTableSeeder::class);
    }
}
