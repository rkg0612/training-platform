<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\InternetShop */
class InternetShopCollection extends ResourceCollection
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($internetShop) {
                return (object) [
                    'title' => $internetShop->is_dealer === 1 ? $this->dealerTitle($internetShop) : $this->competitorTitle($internetShop),
                    'id' => $internetShop->id,
                    'dealer' => $internetShop->dealer,
                    'specific_dealer' => $internetShop->specificDealer,
                    'state' => $internetShop->state,
                    'city' => $internetShop->city,
                ];
            }),
        ];
    }

    private function dealerTitle($internetShop)
    {
        return $internetShop->specificDealer->name.' - '.$internetShop->created_at->tz('EST')->format('M, d, Y H:i:s a');
    }

    private function competitorTitle($internetShop)
    {
        if ($internetShop->competitor) {
            return $internetShop->competitor->name.' - '.$internetShop->created_at->tz('EST')->format('M, d, Y H:i:s a');
        }

        return $internetShop->specificDealer->name.' - '.$internetShop->created_at->tz('EST')->format('M, d, Y H:i:s a');
    }
}
