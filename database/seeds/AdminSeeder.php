<?php

use App\User;
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
        $user = User::create([
            'first_name' => 'Aldo',
            'last_name' => 'Sanchez',
            'email' => 'admin@admin.com',
            'phone' => 3315643212,
            'password' => 123456, // 123456
            'city_id' => 28082,
            'status' => 1,
            'birth_date' => '1996-04-28',
        ]);

        $user->assignRole('admin');
    }
}
