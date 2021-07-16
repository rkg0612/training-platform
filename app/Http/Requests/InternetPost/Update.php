<?php

namespace App\Http\Requests\InternetPost;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

    public function expectsJson()
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
            'verificationScreenshot.*' => 'required|filled',
            'emailScreenshots' => 'array',
            'emailScreenshots.*.file' => 'image',
            'emailScreenshots.*.order' => 'integer',
            'chatScreenshots.*.' => 'image',
            'is_dealer' => 'required',
            'dealer_id' => 'required|exists:dealers,id',
            'specific_dealer_id' => 'required',
            'competitor_id' => 'required_if:dealer.is_dealer,false',
            'zipcode' => 'required',
            'timezone' => [
                Rule::in(['EST', 'CST', 'MST', 'PST', 'AST', 'HST']),
                'required',
            ],
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'is_shop_new' => 'required|boolean',
            'lead_source' => 'required',
            'source_link' => 'required',
            'salesperson_name' => 'required',
            'start_at' => 'required',
            'shop_duration' => 'required',
            'user_id' => 'required|exists:users,id|integer',
            'lead_name' => 'required',
            'lead_email' => 'required',
            'lead_phone_number' => 'required',
            'vehicle_identification_number' => 'required',
            'make' => 'required',
            'model' => 'required',
            //            'existingEmailScreenshots' => 'sometimes|array',
            //            'existingEmailScreenshots.*' => 'sometimes|integer|exists:internet_shop_email_screenshots,id',
            //            'existingChatScreenshots' => 'sometimes|array',
            //            'existingChatScreenshots.*' => 'sometimes|integer|exists:internet_shop_chat_screenshots,id',
        ];
    }

    public function messages()
    {
        return [
            'verificationScreenshot.*.required' => 'The shop verification screenshot is required.',
            'emailScreenshots.*.image' => 'The email screenshot(s) must be an image file.',
            'dealer_id.required' => 'The dealer/competitor group is required.',
            'is_dealer.required' => 'The dealer type is required.',
            'specific_dealer.required' => 'The specific dealer name is required.',
            'zipcode.required' => 'The zipcode is required.',
            'timezone.required' => 'The dealer timezone is required',
            'state_id.required' => 'The state field is required.',
            'city_id.required' => 'The city field is required',
            'is_shop_new.required' => 'The shop type is required.',
            'source.required' => 'The lead source field is required',
            'source_link.required' => 'The shop source link is required',
            'vehicle_identification_number.required' => 'The vehicle identification number is required.',

            'lead_name.required' => 'The lead name field is required',
            'lead_email.required' => 'The lead email field is required.',
            'lead_phoneNumber.required' => 'The lead phone number is required.',
            'user_id.required' => 'The sales peron\'s name is required',
            'start_at.required' => 'The shop start day and time field is required.',
            'shop_duration.required' => 'The shop duration field is required.',
        ];
    }
}
