<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

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
            'data' => $this->formatCollection($this->collection),
        ];
    }

    private function formatCollection(Collection $collection)
    {
        return $this->collection->map(function ($shop) {
            return [
                'id' => $shop->id,
                'dealer' => $shop->dealer->name,
                'shop_type' => $shop->is_shop_new === 1 ? 'New' : 'Used',
                'specific_dealer' => $shop->specificDealer ? $shop->specificDealer->name : null,
                'make' => $shop->make,
                'model' => $shop->model,
                'competitor' => $shop->competitor ? $shop->competitor->name : null,
                //                'rn' => $this->isDealerTrueCar() ? $shop->csm_name : null,
                'dateShopped' => $shop->start_at,
                'type_of_dealer' => $shop->is_dealer === 1 ? 'Dealer' : 'Competitor',
            ];
        });
    }

//    private function isDealerTrueCar()
//    {
//        return auth()->user()->dealer->name === 'TrueCar';
//    }

    private function getSpecificDealer($shop)
    {
        if ($shop->competitor) {
            return $shop->competitor->name;
        }

        if ($shop->specificDealer) {
            return $shop->specificDealer->name;
        }

        return null;
    }
}
