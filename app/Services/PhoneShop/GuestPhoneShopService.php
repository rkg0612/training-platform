<?php

namespace App\Services\PhoneShop;

use App\Models\DealerOption;
use App\Models\PhoneShop;
use Illuminate\Support\Str;

class GuestPhoneShopService
{
    public function show($guestViewId)
    {
        $phoneShop = PhoneShop::query()
            ->where('guest_view_id', $guestViewId)
            ->with([
                'dealer', 'specificDealer', 'state', 'city',
                'dealerRecordings', 'competitorRecordings', 'secretShopper',
            ])->first();

        if (empty($phoneShop)) {
            $phoneShop = PhoneShop::query()
                ->where('id', $guestViewId)
                ->with([
                    'dealer', 'specificDealer', 'state', 'city',
                    'dealerRecordings', 'competitorRecordings', 'secretShopper',
                ])->first();
        }

        if (empty($phoneShop)) {
            return abort('404');
        }

        return response()->json([
            'phoneShop' => $phoneShop,
            'dealer' => DealerOption::where('dealer_id', $phoneShop->dealer_id)->where('name', 'logo_image')->first() ?: 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/main-logo.png',
        ]);
    }

    public function store($request)
    {
        $phoneShop = PhoneShop::find($request->id);

        if ($phoneShop->guest_view_id) {
            return response()->json([
                'id' => $phoneShop->guest_view_id,
            ]);
        }

        $phoneShop->guest_view_id = Str::uuid();

        $phoneShop->save();

        return response()->json([
            'id' => $phoneShop->guest_view_id,
        ]);
    }
}
