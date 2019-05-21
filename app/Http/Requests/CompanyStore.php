<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStore extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|numeric',
            'name' => 'required',
            'description' => 'required',
            'location.int_no' => 'required|numeric',
            'location.lat' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'location.lng' => 'required|regex:/^-?\d+(\.\d+)?$/',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El usuario que intenta asignar a esta empresa no es valido',
            'user_id.numeric' => 'El usuario que intenta asignar a esta empresa no es valido',
            'name.required' => 'El nombre de la empresa es requerido',
            'description.required' => 'La descripcion de la empresa es requerida',
            'location.int_no.required' => 'Debe proporcionar un número interior',
            'location.int_no.numeric' => 'El número interior debe ser numérico',
            'location.lat.required' => 'La latitud de origen es requerida',
            'location.lat.regex' => 'La latitud de origen no tiene un formato valido',
            'location.lng.required' => 'La longitud de origen es requerida',
            'location.lng.regex' => 'La longitud de origen no tiene un formato valido',
        ];
    }
}
