<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email',
            'phone' => 'digits:10',
            'password' => 'string',
            'birth_date' => 'date_format:Y-m-d',
            'location.lat' => 'required|regex:/^-?\d+(\.\d+)?$/',
            'location.lng' => 'required|regex:/^-?\d+(\.\d+)?$/',
        ];
    }

    public function messages()
    {
        return [
            'first_name.string' => 'El nombre del usuario no es valido',
            'last_name.string' => 'El apellido del usuario no es valido',
            'email.required' => 'Debe proporcionar un email para el usuario',
            'email.email' => 'El email proporcionado debe contener un formato de email',
            'password.string' => 'La contraseña introducida no es valida',
            'phone.digits' => 'El telefono debe contener unicamente 10 digitos',
            'phone.required' => 'El telefono del usuario es requerido',
            'birth_date.date_format' => 'La fecha del usuario deberia tener un formato YY-mm-dd',
            'location.lat.required' => 'La latitud de origen es requerida',
            'location.lat.regex' => 'La latitud de origen no tiene un formato valido',
            'location.lng.required' => 'La longitud de origen es requerida',
            'location.lng.regex' => 'La longitud de origen no tiene un formato valido',
        ];
    }
}
