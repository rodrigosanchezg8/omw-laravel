<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinearRegressionByCity extends FormRequest
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
            'city' => 'required',
            'origin_type' => 'digits:1|min:0|max:1',
            'statistics_for' => 'digits:1|min:0|max:1',
        ];
    }

    public function messages()
    {
        return [
            'month_offset.required' => 'Un desplazamiento de mes es requerido',
            'month_offset.numeric' => 'El desplazamiento de mes no esta en un formato correcto',
            'city.required' => 'Debe especificar una ciudad',
            'city.alpha' => 'La ciudad no esta especificada de manera correcta',
            'origin_type.digits' => 'Debe especificar si las estadisticas son para enviador/recibidor con una bandera',
            'statistics_for.digits' => 'Debe especificar si las estadisticas son para cliente/empresa con una bandera',
        ];
    }
}
