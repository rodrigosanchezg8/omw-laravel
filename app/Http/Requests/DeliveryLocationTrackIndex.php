<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeliveryLocationTrackIndex extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_id' => [
                !Auth::user()->hasRole('admin') ? 'required' : '',
                'numeric',
            ],
        ];

    }

    public function messages()
    {
        return [
            'delivery_id.required' => 'Debe proporcionar una entrega',
            'delivery_id.numeric' => 'La entrega proporcionada no tiene un formato valido',
        ];
    }
}
