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
            'delivery_man_id' => 'numeric',
            'sender_id' => 'numeric',
            'receiver_id' => 'numeric',
            'planned_start_date' => 'date:Y-m-d',
            'planned_end_date' => 'date:Y-m-d',
            'departure_date' => 'date:Y-m-d',
            'arrival_date' => 'date:Y-m-d',
            'delivery_status_id' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'delivery_man_id.numeric' => 'El campo repartidor no es valido',
            'sender_id.numeric' => 'La persona que envia el paquete no es valido',
            'receiver_id.numeric' => 'La persona que recibe el paquete no es valida',
            'planned_start_date.date' => 'La fecha planeada de salida no es valida',
            'planned_end_date.date' => 'La fecha planeada de llegada no es valida',
            'departure_date.date' => 'La fecha real de salida no es valida',
            'arrival_date.date' => 'La fecha real de llegada no es valida',
            'delivery_status_id.numeric' => 'El id de la entrega no tiene un formato valido',
        ];
    }
}
