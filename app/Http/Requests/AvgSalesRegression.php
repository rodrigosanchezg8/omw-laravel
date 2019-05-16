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
        ];
    }

    public function messages()
    {
        return [
            'month_offset.required' => 'Un desplazamiento de mes es requerido',
            'month_offset.numeric' => 'El desplazamiento de mes no esta en un formato correcto',
        ];
    }
}
