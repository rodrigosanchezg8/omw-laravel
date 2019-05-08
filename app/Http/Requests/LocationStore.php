<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lat' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'lng' => 'required|regex:/^-?\d+(\.\d+)?$/',
        ];
    }

    public function messages()
    {
        return [
            'lat.required' => 'La latitud de origen es requerida',
            'lat.regex' => 'La latitud de origen no tiene un formato valido',
            'lng.required' => 'La longitud de origen es requerida',
            'lng.regex' => 'La longitud de origen no tiene un formato valido',
        ];
    }
}
