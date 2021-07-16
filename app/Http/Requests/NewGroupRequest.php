<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewGroupRequest extends FormRequest
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
            'name' => 'required|string',
            'users' => 'required|array',
            'users.*' => 'required|integer|exists:users,id',
            'dealer_id' => 'sometimes|nullable|integer|exists:dealers,id',
            'specific_dealer_id' => 'sometimes|nullable|integer|exists:specific_dealers,id',
        ];
    }
}
