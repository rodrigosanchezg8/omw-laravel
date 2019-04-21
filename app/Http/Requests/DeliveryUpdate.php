<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryUpdate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_man_id' => 'digits:1',
            'sender_id' => 'digits:1',
            'receiver_id' => 'digits:1',
            'planned_start_date' => 'date:Y-m-d',
            'planned_end_date' => 'date:Y-m-d',
            'departure_date' => 'date:Y-m-d',
            'arrival_date' => 'date:Y-m-d',
            'departure_location_id' => 'digits:1',
            'arrival_location_id' => 'digits:1',
            'delivery_status_id' => 'digits:1',
        ];
    }

    public function messages()
    {
        return [
            'delivery_man_id.digits' => 'El campo repartidor no es valido',
            'sender_id.digits' => 'La persona que envia el paquete no es valido',
            'receiver_id.digits' => 'La persona que recibe el paquete no es valida',
            'planned_start_date.date' => 'La fecha planeada de salida no es valida',
            'planned_end_date.date' => 'La fecha planeada de llegada no es valida',
            'departure_date.date' => 'La fecha real de salida no es valida',
            'arrival_date.date' => 'La fecha real de llegada no es valida',
            'departure_location_id.digits' => 'La localizacion de salida no es valida',
            'arrival_location_id.digits' => 'La localizacion de llegada no es valida',
        ];
    }
}
