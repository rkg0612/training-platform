<?php

namespace App\Services\PhoneShop;

use App\Mail\PreviewPhoneShopReport;
use App\Models\DealerOption;
use App\Models\PhoneShop;
use App\Models\SpecificDealer;
use Carbon\Carbon;

class PreviewPhoneShopReportService
{
    public $phoneShop;

    public function __construct(PhoneShop $phoneShop)
    {
        $this->phoneShop = $phoneShop;
    }

    public function show($id)
    {
        $phoneShop = PhoneShop::find($id);

        $template = $phoneShop->inbound_call_grade;

        $image = DealerOption::query()
            ->where('name', 'logo_image')
            ->where('dealer_id', $phoneShop->specific_dealer_id)
            ->first();

        $bgColor = DealerOption::query()
            ->where('name', 'logo_bg_color')
            ->where('dealer_id', $phoneShop->specific_dealer_id)
            ->first();

        $specificDealer = SpecificDealer::find($phoneShop->specific_dealer_id)->name;

        $shopDate = Carbon::parse($phoneShop->start_date)->format('m-d-Y');

        $link = route('index', [
            'any' => '/guest/phone-shop/'.$id,
        ]);

        return (new PreviewPhoneShopReport($template, $specificDealer, $shopDate, $image, $link, $bgColor))->render();
    }
}
