<?php

namespace App\Services\InternetShop;

use App\Models\InternetShop;

class GuestInternetShopService
{
    public function show($id)
    {
        return InternetShop::with(['source', 'competitor', 'dealer', 'dealer.options', 'truecar_fields', 'chatScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'desc');
        }, 'emailScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'postedBy', 'specificDealer', 'state', 'city', 'phoneNumber.sms' => function ($message) {
            return $message->orderBy('start_at', 'asc');
        }, 'phoneNumber.sms.medias', 'phoneNumber.calls'])->findOrFail($id);
    }
}
