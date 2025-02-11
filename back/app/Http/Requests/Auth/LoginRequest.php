<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'exists:users,phone'
            ],
            'password' => [
                'required'
            ]
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'Такого пользователя не существует',
            'password.required' => 'Поле "Пароль" обязательно для заполнения'
        ];
    }
}
