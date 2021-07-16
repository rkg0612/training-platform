<?php

namespace App\Http\Requests\PhoneShop;

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
            'id' => 'required|exists:phone_shops,id',
            'dealer_id' => 'exists:dealers,id|required',
            'specific_dealer_id' => 'required',
            'state_id' => 'exists:states,id|required',
            'city' => 'required',
            'shop_type' => 'required|boolean',
            'sales_person_name' => 'required',
            'start_date' => 'required',
            'zip_code' => 'required',
            'inbound_call_grade' => 'required',
            'lead_name' => 'required',
            'dealerRecordingsLabel' => 'nullable',
            'dealerRecordingsRemoved.*' => 'nullable',
            'competitorRecordingsLabel' => 'nullable',
            'competitorRecordingsRemoved.*' => 'nullable',
            'secret_shopper_id' => 'required|exists:users,id',
        ];
    }
}
