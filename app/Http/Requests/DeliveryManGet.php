<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryManGet extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'origin_lat' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'origin_lng' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'destiny_lat' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'destiny_lng' => 'required|regex:/^-?\d+(\.\d+)?$/',
        ];
    }

    public function messages()
    {
        return [
            'origin_lat.required' => 'La latitud de origen es requerida',
            'origin_lat.regex' => 'La latitud de origen no tiene un formato valido',
            'origin_lng.required' => 'La longitud de origen es requerida',
            'origin_lng.regex' => 'La longitud de origen no tiene un formato valido',
            'destiny_lat.required' => 'La latitud de destino es requerida',
            'destiny_lat.regex' => 'La latitud de destino no tiene un formato valido',
            'destiny_lng.required' => 'La longitud de destino es requerida',
            'destiny_lng.regex' => 'La longitud de destino no tiene un formato valido',
        ];
    }
}
