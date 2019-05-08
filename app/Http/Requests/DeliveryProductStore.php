<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryProductStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_id' => 'required|numeric',
            'name' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'product_image' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'delivery_id.required' => 'El id de entrega es requerido',
            'delivery_id.numeric' => 'El id de entrega no es valido',
            'name.required' => 'El nombre del producto es obligatorio',
            'amount.required' => 'La cantidad del producto es obligatoria',
            'amount.regex' => 'La cantidad del producto ingresada no es valida',
            'cost.required' => 'El costo del producto es obligatoria',
            'cost.regex' => 'El costo del producto ingresado no es valida',
            'product_image.required' => 'Una imagen del producto es requerida',
        ];
    }
}
