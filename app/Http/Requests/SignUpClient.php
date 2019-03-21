<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpClient extends FormRequest
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
            'city_id' => 'digits:1',
            'birth_date' => 'date_format:Y-m-d',
            'profile_photo' => 'image',
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
            'phone.required' => 'El telefono del usuario es requerido',
            'phone.digits' => 'El telefono debe contener unicamente 10 digitos',
            'city_id.required' => 'La ciudad del usuario no es valida',
            'profile_photo.image' => 'La foto de perfil debe ser una imagen valida',
        ];
    }
}
