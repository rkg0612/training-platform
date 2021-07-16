<?php

namespace App\Http\Requests\InternetShop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

    public function expectsJson()
    {
        return true;
    }

    public function validationData()
    {
        $requests = [];
        foreach ($this->instance()->all() as $head => $body) {
            if (is_array($body) || $head == 'verificationScreenshot') {
                $requests[$head] = $body;
            } else {
                $requests[$head] = json_decode($body, true);
            }
        }
//        $requests = [];
//        foreach ($this->instance()->all() as $head => $body) {
//            if ($head == 'verificationScreenshot' || is_array($body)) {
//                $requests[$head] = $body;
//            } else {
//                $requests[$head] = (array) json_decode($body);
//            }
//        }

        return $requests;
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
            'dealer.is_dealer' => 'required|boolean',
            'dealer.id' => 'required|exists:dealers,id',
            'dealer.name' => 'required',
            'dealer.competitor' => 'required_if:dealer.is_dealer,false',
            'dealer.zipcode' => 'required',
            'dealer.timezone' => [
                Rule::in(['EST', 'CST', 'MST', 'PST', 'AST', 'HST']),
                'required',
            ],
            'location.state' => 'required|exists:states,id',
            'location.city' => 'required|exists:cities,id',
            'shop.is_new' => 'required|boolean',
            'shop.sourceLink' => 'required',
            'shop.salesPersonName' => 'required',
            'shop.csmName' => 'present',
            'shop.callGuideLink' => 'present',
            'shop.startAt' => 'required',
            'shop.duration' => 'required',
            'shop.secretShopperId' => 'required|exists:users,id|integer',
            'lead.*' => 'required',
            'car.*' => 'required',
            'call' => 'required|array',
            'sms' => 'required|array',
            'email' => 'required|array',
            'chat.*' => 'present',
        ];
    }

    public function messages()
    {
        return [
            'verificationScreenshot.*.required' => 'The shop verification screenshot is required.',
            'emailScreenshots.*.image' => 'The email screenshot(s) must be an image file.',
            'dealer.id.required' => 'The dealer/competitor group is required.',
            'dealer.is_dealer.required' => 'The dealer type is required.',
            'dealer.name.required' => 'The specific dealer name is required.',
            'dealer.zipcode.required' => 'The zipcode is required.',
            'dealer.timezone.required' => 'The dealer timezone is required',
            'location.state.required' => 'The state field is required.',
            'location.city.required' => 'The city field is required',
            'shop.is_new.required' => 'The shop type is required.',
            'lead.source.required' => 'The lead source field is required',
            'shop.sourceLink' => 'The shop source link is required',
            'car.vin' => 'The vehicle identification number is required.',

            'lead.name.required' => 'The lead name field is required',
            'lead.email.required' => 'The lead email field is required.',
            'lead.phoneNumber.required' => 'The lead phone number is required.',
            'shop.salesPersonName.required' => 'The sales peron\'s name is required',
            'shop.csmName.required' => 'The csm name field is required.',
            'shop.startAt.required' => 'The shop start day and time field is required.',
            'shop.duration.required' => 'The shop duration field is required.',
        ];
    }
}
