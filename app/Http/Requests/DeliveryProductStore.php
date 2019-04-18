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
            'delivery_man_id' => 'required|digits:1',
            'name' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'product_image' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'delivery_man_id.required' => 'El repartidor es requerido',
            'delivery_man_id.digits' => 'El repartidor ingresado no es valido',
            'name.required' => 'El nombre del producto es obligatorio',
            'amount.required' => 'La cantidad del producto es obligatoria',
            'amount.regex' => 'La cantidad del producto ingresada no es valida',
            'cost.required' => 'El costo del producto es obligatoria',
            'cost.regex' => 'El costo del producto ingresada no es valida',
            'product_image.image' => 'La imagen del producto ingresada no es valida',
        ];
    }
}
