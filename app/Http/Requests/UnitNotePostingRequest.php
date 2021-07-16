<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitNotePostingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'unitId' => 'required|exists:units,id',
            'value' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
