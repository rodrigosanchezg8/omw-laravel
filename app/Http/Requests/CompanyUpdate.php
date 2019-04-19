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
            'user_id' => 'required|numeric',
            'name' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El usuario que intenta asignar a esta empresa no es valido',
            'user_id.numeric' => 'El usuario que intenta asignar a esta empresa no es valido',
            'name.required' => 'El nombre de la empresa es requerido',
            'description.required' => 'La descripcion de la empresa es requerida',
        ];
    }
}
