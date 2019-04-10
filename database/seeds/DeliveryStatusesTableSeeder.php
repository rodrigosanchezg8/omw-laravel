<?php

use App\DeliveryStatus;
use Illuminate\Database\Seeder;

class DeliveryStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'No Iniciado',
            'En Progreso',
            'Entregado',
            'Cancelado',
        ];

        foreach ($statuses as $status) {
            DeliveryStatus::create([
                'status' => $status,
            ]);
        }
    }
}
