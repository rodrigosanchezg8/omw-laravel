<?php

use Illuminate\Database\Seeder;

class ServiceRangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_ranges')->delete();
        $service_ranges = [
            ['km' => '50', 'description' => 'short'],
            ['km' => '125', 'description' => 'medium'],
            ['km' => '250', 'description' => 'long'],
        ];
        DB::table('service_ranges')->insert($service_ranges);
    }
}
