<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.auth()->user()->id,
            'password' => 'nullable',
            'password_confirmation' => 'confirmed',
        ];
    }
}
