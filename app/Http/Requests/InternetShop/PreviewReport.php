<?php

namespace App\Http\Requests\InternetShop;

use Illuminate\Foundation\Http\FormRequest;

class PreviewReport extends FormRequest
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

    public function validationData()
    {
        return $this->route()->parameters();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:internet_shops,id',
        ];
    }
}
