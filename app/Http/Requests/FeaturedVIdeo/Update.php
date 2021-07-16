<?php

namespace App\Http\Requests\FeaturedVideo;

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
            'id' => 'required|exists:featured_videos,id',
            'name' => 'required',
            'videoUrl' => 'required|url',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ];
    }
}
