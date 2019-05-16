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
            'for_company' => 'digits:1|min:0|max:1',
        ];
    }

    public function messages()
    {
        return [
            'month_offset.required' => 'Un desplazamiento de mes es requerido',
            'month_offset.numeric' => 'El desplazamiento de mes no esta en un formato correcto',
            'for_company.digits' => 'No se especifico si la prediccion es para compania de manera correcta',
            'for_company.min' => 'El valor minimo es cero',
            'for_company.max' => 'El valor maximo es uno', 
        ];
    }
}
