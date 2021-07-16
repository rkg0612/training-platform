<?php

namespace App\Services;

use App\Models\DealerOption;
use App\Models\GroupShop;

class GuestGroupShopViewService
{
    public function show($id)
    {
        $groupShop = GroupShop::query()
            ->where('id', $id)
            ->orWhere('guest_view_id', $id)
            ->with([
                'dealer', 'specificDealer', 'internetShops.competitor',
                'internetShops.specificDealer', 'internetShops.source', 'internetShops.chatScreenshots' => function ($screenshot) {
                    return $screenshot->orderBy('order_by', 'desc');
                }, 'internetShops.emailScreenshots' => function ($screenshot) {
                    return $screenshot->orderBy('order_by', 'desc');
                }, 'internetShops.specificDealer', 'internetShops.phoneNumber.sms', 'internetShops.phoneNumber.calls' => function ($call) {
                    return $call->orderBy('created_at', 'desc');
                },
                'phoneShops.specificDealer', 'phoneShops.dealerRecordings', 'phoneShops.competitorRecordings',
            ])
            ->firstOrFail();

        if (empty($groupShop)) {
            return abort('404');
        }

        $logo = DealerOption::query()
            ->where('dealer_id', $groupShop->dealer_id)
            ->where('name', 'logo_image')
            ->first();

        if (empty($logo)) {
            $logo = 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/main-logo.png';
        }

        return response()->json([
            'groupShop' => $groupShop,
            'dealer' => $logo,
        ]);
    }
}
