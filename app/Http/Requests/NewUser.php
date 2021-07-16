<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class NewUser extends FormRequest
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
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email:filter|unique:users,email',
            'phone_number' => 'sometimes|string|nullable',
            'password' => 'required|string|confirmed',
            'dealer_id' => 'sometimes|integer|nullable|exists:dealers,id',
            'roles' => 'required|array',
            'roles.*' => 'required|integer|exists:roles,id',
        ];

        $roleNames = Role::whereIn('id', request()->roles)->pluck('name')->all();

        if (in_array('specific-dealer-manager', $roleNames)) {
            $additionalRules = [
                'specific_dealer_id' => 'required|array',
                'specific_dealer_id.name' => 'required|regex:/^[a-zA-Z0-9 ]+$/i',
            ];
            $rules = $rules + $additionalRules;
        }
        \Log::debug($rules);

        return $rules;
    }
}
