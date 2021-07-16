<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompareDealerPerformanceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'date' => 'sometimes|array',
            'dealerOne' => 'required|exists:specific_dealers,id',
            'dealerTwo' => 'required|exists:specific_dealers,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
