<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryChangeStatus extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_status_id' => 'required|digits:1',
        ];

    }
    public function messages()
    {
        return [
            'delivery_status_id.required' => 'El campo status es obligatorio',
            'delivery_status_id.digits' => 'EL campo status ingresado no es valido',
        ];
    }
}
