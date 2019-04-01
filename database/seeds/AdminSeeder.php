<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Aldo',
            'last_name' => 'Sanchez',
            'email' => 'admin@admin.com',
            'phone' => 3315643212,
            'password' => '$2y$10$JHlSPqBa/ydtc1Ml5RlztOPdg05hsc22hXO249vkoPxqBScnwzX/S', // 123456
            'city_id' => 28082,
            'status' => 1,
            'birth_date' => '1996-04-28',
        ]);
    }
}
