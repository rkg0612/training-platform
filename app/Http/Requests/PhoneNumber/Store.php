<?php

namespace App\Http\Requests\PhoneNumber;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'state' => 'required|exists:states,id',
            'area_code' => 'required',
            'number' => 'required|unique:phone_numbers,value',
            'dealer' => 'required|exists:dealers,id',
            'audio' => 'required|exists:voice_mails,id',
            'category' => 'required',
            'secret_shopper' => 'required|exists:users,id',
        ];
    }
}
