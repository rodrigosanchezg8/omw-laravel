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
        Role::create(['name' => 'admin', 'guard_name' => 'api', 'alias' => 'Administrador']);
        Role::create(['name' => 'delivery_man', 'guard_name' => 'api', 'alias' => 'Repartidor']);
        Role::create(['name' => 'company', 'guard_name' => 'api', 'alias' => 'Empresa']);
        Role::create(['name' => 'client', 'guard_name' => 'api', 'alias' => 'Cliente']);
    }
}
