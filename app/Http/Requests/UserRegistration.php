<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistration extends FormRequest
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
            'full_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
            'temporary_dealer' => 'required',
            'terms_and_conditions' => 'required|boolean:1',
        ];
    }
}
