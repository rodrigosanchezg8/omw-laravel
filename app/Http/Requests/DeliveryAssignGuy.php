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
            'delivery_man_id' => 'required|digits:1',
        ];
    }

    public function messages()
    {
        return [
            'delivery_man_id.required' => 'El repartidor es un campo requerido',
            'delivery_man_id.digits' => 'El repartidor proporcionado no es valido',
        ];
    }
}
