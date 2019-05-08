<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryProductUpdate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_id' => 'numeric',
            'amount' => 'regex:/^\d+(\.\d{1,2})?$/',
            'cost' => 'regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function messages()
    {
        return [
            'delivery_id.numeric' => 'El repartidor ingresado no es valido',
            'amount.regex' => 'La cantidad del producto ingresada no es valida',
            'cost.regex' => 'El costo del producto ingresada no es valida',
        ];
    }
}
