<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryManServiceOptions extends FormRequest
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
            'user_id' => 'required',
            'service_range_id' => 'required',
            'available' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'user_id.required' => 'El usuario es requerido',
            'service_range_id.required' => 'El rango de servicio es requerido',
            'available.required' => 'La disponibilidad es requerida',
        ];
    }

}
