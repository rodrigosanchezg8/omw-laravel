<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryAssignGuy extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_man_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'delivery_man_id.required' => 'El repartidor es un campo requerido',
            'delivery_man_id.numeric' => 'El repartidor proporcionado no es valido',
        ];
    }
}
