<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/** @see \App\Models\PhoneShop */
class PhoneShopCollection extends ResourceCollection
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
                'company' => $shop->dealer->name,
                'specific_dealer' => $shop->specificDealer->name,
                'start_date' => $shop->start_date,
                'shop_type' => $shop->shop_type === 1 ? 'New' : 'Used',
            ];
        });
    }
}
