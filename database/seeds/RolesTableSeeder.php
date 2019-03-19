<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin', 'guard_name' => 'admin', 'alias' => 'Administrador']);
        Role::create(['name' => 'delivery_man', 'guard_name' => 'delivery_man', 'alias' => 'Repartidor']);
        Role::create(['name' => 'company', 'guard_name' => 'company', 'alias' => 'Empresa']);
        Role::create(['name' => 'client', 'guard_name' => 'client', 'alias' => 'Cliente']);
    }
}
