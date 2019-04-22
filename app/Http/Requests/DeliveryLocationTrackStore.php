<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryLocationTrackStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_id' => 'required|digits:1',
            'location_id' => 'required|digits:1',
            'step' => 'required|integer|min:0',
        ];

    }
    public function messages()
    {
        return [
            'delivery_id.required' => 'Es necesario proporcionar un pedido para la localizacion',
            'delivery_id.digits' => 'El pedido ingresado no esta en un formato valido',
            'location_id.required' => 'Es necesario proporcionar una localizacion',
            'location_id.digits' => 'La localizacion ingresada no esta en un formato valido',
            'step.required' => 'Es necesario especificar el step de la localizacion',
            'step.integer' => 'El step no se encuentra en un formato valido',
            'step.min' => 'El step minimo es cero',
        ];
    }
}