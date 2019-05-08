<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpCompany extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
            'profile_photo' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El usuario que intenta asignar a esta empresa no es valido',
            'user_id.numeric' => 'El usuario que intenta asignar a esta empresa no es valido',
            'name.required' => 'El nombre de la empresa es requerido',
            'description.required' => 'La descripcion de la empresa es requerida',
            'profile_photo.image' => 'La foto de perfil de la empresa tiene que ser una imagen valida',
        ];
    }
}
