<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\GroupShop */
class GroupShopCollection extends ResourceCollection
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($groupShop) {
                return (object) [
                    'id' => $groupShop->id,
                    'name' => $groupShop->name,
                    'dealer_id' => $groupShop->dealer_id,
                    'dealer' => $groupShop->dealer,
                    'specific_dealer_id' => $groupShop->specific_dealer_id,
                    'specific_dealer' => $groupShop->specificDealer,
                    'hide_dealer_name' => $groupShop->hide_dealer_name,
                    'internet_shops' => $groupShop->internetShops->map(function ($internetShop) {
                        return (object) [
                            'title' => $internetShop->specificDealer->name.' - '.$internetShop->created_at->format('M, d, Y H:i:s a'),
                            'id' => $internetShop->id,
                            'dealer' => $internetShop->dealer,
                            'specific_dealer' => $internetShop->specificDealer,
                            'state' => $internetShop->state,
                            'city' => $internetShop->city,
                        ];
                    }),
                    'phone_shops' => $groupShop->phoneShops->map(function ($phoneShop) {
                        return (object) [
                            'title' => $phoneShop->specificDealer->name.' - '.$phoneShop->created_at->format('M, d, Y H:i:s a'),
                            'id' => $phoneShop->id,
                            'dealer' => $phoneShop->dealer,
                            'specific_dealer' => $phoneShop->specificDealer,
                            'state' => $phoneShop->state,
                            'city' => $phoneShop->city,
                        ];
                    }),
                    'secret_shopper' => $groupShop->secretShopper,
                    'deleted_at' => $groupShop->deleted_at,
                    'created_at' => $groupShop->created_at,
                ];
            }),
        ];
    }
}
