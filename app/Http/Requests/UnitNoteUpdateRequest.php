<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitNoteUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'value' => 'required',
            'id' => 'required|exists:notes,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
