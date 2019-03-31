<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'digits:1',
            'name' => 'string',
            'description' => 'string',
            'city_id' => 'digits:1',
            'profile_image' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'user_id.digits' => 'El usuario que intenta asignar a esta empresa no es valido',
            'name.string' => 'El nombre ingresado no es valido',
            'description.string' => 'La descripcion ingresada no es valida',
            'city_id.digits' => 'La ciudad que intenta registrar no es valida',
            'profile_image' => 'La foto de perfil de la empresa tiene que ser una imagen valida',
        ];
    }
}
