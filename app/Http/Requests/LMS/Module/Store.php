<?php

namespace App\Http\Requests\LMS\Module;

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
                'name'          =>  'required',
                'category_id'   =>  'exists:categories,id|required',
                'description'   =>  'required',
            ];
        }

        public function messages()
        {
            return [
                'name.required' => 'The name field is required.',
                'category_id.required' => 'The category field is required.',
                'description.required' => 'The description field is required.',
            ];
        }
    }
