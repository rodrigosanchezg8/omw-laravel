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
            'sender_id' => 'numeric',
            'receiver_id' => 'numeric',
            'delivery_man_id' => 'numeric',
            'company_is_sending' => 'digits_between:0,1',
            'planned_start_date' => 'date_format:Y-m-d H:i:s',
            'planned_end_date' => 'date_format:Y-m-d H:i:s',
            'departure_date' => 'date_format:Y-m-d H:i:s',
            'arrival_date' => 'date_format:Y-m-d H:i:s',
            'delivery_status_id' => 'numeric',
            'score' => 'regex:/^\d+(\.\d+)?$/',
        ];
    }

    public function messages()
    {
        return [
            'sender_id.numeric' => 'La persona que envia el paquete no es valido',
            'receiver_id.numeric' => 'La persona que recibe el paquete no es valida',
            'delivery_man_id.numeric' => 'El campo repartidor no es valido',
            'company_is_sending.digits_between' => 'Company_is_sending no especificado de manera correcta',
            'planned_start_date.date_format' => 'La fecha planeada de salida no es valida',
            'planned_end_date.date_format' => 'La fecha planeada de llegada no es valida',
            'departure_date.date_format' => 'La fecha real de salida no es valida',
            'arrival_date.date_format' => 'La fecha real de llegada no es valida',
            'delivery_status_id.numeric' => 'El id de la entrega no tiene un formato valido',
            'score.regex' => 'Calificacion no proporcionada de manera correcta',
        ];
    }
}
