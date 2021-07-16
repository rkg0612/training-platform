<?php

namespace App\Http\Requests\GroupShop;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{
    private $user;

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->user = auth()->user();
    }

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
            'name' => 'required',
            'phone_shops.*' => 'nullable|exists:phone_shops,id',
            'internet_shops.*' => 'nullable|exists:internet_shops,id',
            'hide_dealer_name' => 'nullable',
        ];

        if ($this->user->roles->contains('name', Role::SUPER_ADMINISTRATOR) ||
            $this->user->roles->contains('name', Role::SECRET_SHOPPER)) {
            $rules['dealer_id'] = 'required';
            $rules['specific_dealer_id'] = 'required';
        }

        if ($this->user->roles->contains('name', Role::ACCOUNT_MANAGER)) {
            $rules['dealer_id'] = 'nullable';
            $rules['specific_dealer_id'] = 'required';
        }

        if ($this->user->roles->contains('name', Role::SPECIFIC_DEALER_MANAGER)) {
            $rules['dealer_id'] = 'nullable';
            $rules['specific_dealer_id'] = 'nullable';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Group Shop Name is required.',
            'internet_shops.required' => 'The Internet Shops is required',
            'assigned_dealer.required' => 'The dealer is required.',
            'phone_shops.required' => 'The Phone Shops is required.',
            'specific_dealer_id.required' => 'The Specific Dealer is required.',
        ];
    }
}
