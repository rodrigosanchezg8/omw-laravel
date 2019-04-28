<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sender_id' => 'required|numeric',
            'receiver_id' => 'required|numeric',
            'planned_start_date' => 'date:Y-m-d',
            'planned_end_date' => 'date:Y-m-d',
            'departure_date' => 'date:Y-m-d',
            'arrival_date' => 'date:Y-m-d',
            'delivery_status_id' => 'digits:1',
        ];
    }

    public function messages()
    {
        return [
            'sender_id.required' => 'La persona que envia el paquete es obligatoria',
            'sender_id.numeric' => 'La persona que envia el paquete no es valido',
            'receiver_id.required' => 'La persona que recibe el paquete es obligatoria',
            'receiver_id.numeric' => 'La persona que recibe el paquete no es valida',
            'planned_start_date.date' => 'La fecha planeada de salida no es valida',
            'planned_end_date.date' => 'La fecha planeada de llegada no es valida',
            'departure_date.date' => 'La fecha real de salida no es valida',
            'arrival_date.date' => 'La fecha real de llegada no es valida',
        ];
    }
}
