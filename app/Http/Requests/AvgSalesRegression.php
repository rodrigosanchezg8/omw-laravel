<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvgSalesRegression extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'month_offset' => 'required|numeric',
            'sales_avg' => 'required|regex:/^-?\d+(\.\d+)?$/',
        ];
    }

    public function messages()
    {
        return [
            'month_offset.required' => 'Un desplazamiento de mes es requerido',
            'month_offset.numeric' => 'El desplazamiento de mes no esta en un formato correcto',
            'sales_avg.required' => 'Es necesario que especifique un promedio de venta para predecir',
            'sales_avg.regex' => 'El promedio de ventas no esta en un formato correcto',
        ];
    }
}
