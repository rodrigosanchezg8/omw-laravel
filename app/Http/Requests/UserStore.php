<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStore extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'password' => 'required|confirmed',
            'role.name' => 'required|string',
            'city_id' => 'numeric',
            'birth_date' => 'date:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El nombre del usuario es requerido',
            'last_name.required' => 'El apellido del usuario es requerido',
            'email.required' => 'Debe proporcionar un email para el usuario',
            'email.email' => 'El email proporcionado debe contener un formato de email',
            'password.required' => 'Debe proporcionar una clave para el usuario',
            'password.confirmed' => 'La clave y la confirmacion de clave deben ser identicas',
            'role.required' => 'Debe proporcionar un rol para el usuario',
            'role.name.required' => 'Debe proporcionar un rol para el usuario',
            'phone.required' => 'El telefono del usuario es requerido',
            'phone.digits' => 'El telefono debe contener unicamente 10 digitos',
            'city_id.digits' => 'La ciudad ingresada tiene que ser un numero entero',
            'birth_date.date_format' => 'La fecha ingresada no es valida',
        ];
    }
}
