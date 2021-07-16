<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class PhoneShopCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($phoneShop) {
                return (object) [
                    'title' => $phoneShop->specificDealer->name.' - '.$phoneShop->created_at->tz('EST')->format('M, d, Y H:i:s a'),
                    'id' => $phoneShop->id,
                    'dealer' => $phoneShop->dealer,
                    'specific_dealer' => $phoneShop->specificDealer,
                    'state' => $phoneShop->state,
                    'city' => $phoneShop->city,
                    'dealer_recordings' => $phoneShop->dealerRecordings->map(function ($recording) {
                        return (object) [
                            'value' => $recording->value,
                            'mp3' => $recording->playableAudio,
                            'duration' => $recording->duration,
                            'created_at' => $recording->created_at,
                            'label' => $recording->label,
                        ];
                    })->sortBy('created_at'),
                    'competitor_recordings' => $phoneShop->competitorRecordings->map(function ($recording) {
                        return (object) [
                            'value' => $recording->value,
                            'mp3' => $recording->playableAudio,
                            'duration' => $recording->duration,
                            'created_at' => $recording->created_at,
                            'label' => $recording->label,
                        ];
                    })->sortBy('created_at'),
                    'secret_shopper' => $phoneShop->secretShopper,
                    'shop_type' => $phoneShop->shop_type,
                    'sales_person_name' => $phoneShop->sales_person_name,
                    'lead_name' => $phoneShop->lead_name,
                    'inbound_call_grade' => $phoneShop->inbound_call_grade,
                    'start_date' => $phoneShop->start_date,
                    'deleted_at' => $phoneShop->deleted_at,
                    'created_at' => $phoneShop->created_at,
                    'zip_code' => $phoneShop->zip_code,
                ];
            }),
        ];
    }
}
