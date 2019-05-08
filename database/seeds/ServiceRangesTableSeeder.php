<?php

use Illuminate\Database\Seeder;

class ServiceRangesTableSeeder extends Seeder
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
            ['km' => '50', 'description' => 'Local'],
            ['km' => '125', 'description' => 'Corta'],
            ['km' => '250', 'description' => 'Media'],
            ['km' => '500', 'description' => 'Media larga'],
            ['km' => '700', 'description' => 'Larga'],
            ['km' => '1000', 'description' => 'Muy larga'],
        ];
        DB::table('service_ranges')->insert($service_ranges);
    }
}
