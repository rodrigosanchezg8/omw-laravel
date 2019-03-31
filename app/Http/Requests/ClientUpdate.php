<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdate extends FormRequest
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
            'city_id' => 'digits:1',
            'status' => 'digits:1',
            'birth_date' => 'date_format:Y-m-d',
            'profile_photo' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'first_name.string' => 'El nombre del usuario no es valido',
            'last_name.string' => 'El apellido del usuario no es valido',
            'email.email' => 'El email proporcionado debe contener un formato de email',
            'password.string' => 'La contraseña introducida no es valida',
            'phone.digits' => 'El telefono debe contener unicamente 10 digitos',
            'city_id.digits' => 'La ciudad del usuario tiene que ser un numero entero',
            'status.digits' => 'La ciudad del usuario tiene que ser un numero entero',
            'birth_date.date_format' => 'La fecha del usuario deberia tener un formato YY-mm-dd',
            'profile_photo.image' => 'La foto de perfil debe ser una imagen valida',
        ];
    }
}
