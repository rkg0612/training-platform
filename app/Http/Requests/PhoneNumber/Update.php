<?php

namespace App\Http\Requests\PhoneNumber;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'id' => "required|unique:phone_numbers,id,{$this->request->get('id')}",
            'state' => 'required|exists:states,id',
            'area_code' => 'required',
            'number' => "required|unique:phone_numbers,value,{$this->request->get('id')}",
            'dealer' => 'required|exists:dealers,id',
            'audio' => 'required|exists:voice_mails,id',
            'category' => 'required',
            'secret_shopper' => 'required|exists:users,id',
        ];
    }
}
