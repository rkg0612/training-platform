<?php

namespace App\Http\Requests\EventCalendar;

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
            'name' => 'required',
            'url' => 'required|url',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'color' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'url.required' => 'The registration url field is required.',
            'url.url' => 'The url field must be a valid url.',
            'start_at.required' => 'The start date field is required.',
            'start_at.date' => 'The start date field must be a valid date.',
            'end_at.required' => 'The end date field is required.',
            'end_at.date' => 'The end date field must be a valid date.',
            'end_at.after_or_equal' => 'The end date field must be after or equal to start date.',
        ];
    }
}
