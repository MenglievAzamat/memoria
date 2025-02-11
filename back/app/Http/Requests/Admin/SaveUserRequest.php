<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->type === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => [
                'required',
                'unique:users,phone'
            ],
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения',
            'phone.unique' => 'Такой пользователь уже существует'
        ];
    }
}
