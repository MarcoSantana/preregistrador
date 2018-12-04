<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            //
            'email' => 'email|unique:users|max:255',
            'name' => 'string|between:10,50',
            'password' => 'required'
        ];
    }

    /**
     * Sets the error messages for a failed request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.string' => 'El nombre debe ser texto',
            'name.between' => 'El nombre debe tener un mínimo de 10 caracteres y un máximo de 50',
            'email.email' => 'El correo electrónico debe ser un correo válido. Ej. usuario@correopersonal.com',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado'
        ];
    }
}
