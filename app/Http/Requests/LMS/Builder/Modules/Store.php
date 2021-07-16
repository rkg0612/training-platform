<?php

namespace App\Http\Requests\LMS\Builder\Modules;

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
            'module_id'                 =>  'exists:modules,id|required|unique:module_build,module_id',
            'unitSection.*.name'        =>  'required',
            'unitSection.*.units.*'     =>  'exists:units,id|unique:series_units,unit_id',
        ];
    }
}
