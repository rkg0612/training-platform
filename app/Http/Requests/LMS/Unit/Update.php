<?php

namespace App\Http\Requests\LMS\Unit;

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
            'name'              =>  'required',
            'module_id'         =>  'required|exists:modules,id',
            'description'       =>  'required',
            'thumbnail'         =>  'nullable',
            'video_duration'    =>  'required',
            'content'           =>  'required',
            'tags'              =>  'required',
        ];
    }
}
