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
            'delivery_id' => 'required|numeric',
        ];

    }

    public function messages()
    {
        return [
            'delivery_id.required' => 'Es necesario proporcionar un pedido para la localizacion',
            'delivery_id.numeric' => 'El pedido ingresado no esta en un formato valido',
        ];
    }
}
