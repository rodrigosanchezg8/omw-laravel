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
            'user_id' => 'required|digits:1',
            'name' => 'required',
            'description' => 'required',
            'city_id' => 'digits:1',
            'profile_photo' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El usuario que intenta asignar a esta empresa no es valido',
            'user_id.digits' => 'El usuario que intenta asignar a esta empresa no es valido',
            'name.required' => 'El nombre de la empresa es requerido',
            'description.required' => 'La descripcion de la empresa es requerida',
            'city_id.digits' => 'La ciudad que intenta registrar no es valida',
            'profile_photo.image' => 'La foto de perfil de la empresa tiene que ser una imagen valida',
        ];
    }
}
