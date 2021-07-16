<?php

namespace App\Services\InternetShop;

use App\Mail\InternetShopReport;
use App\Models\InternetShop;
use Illuminate\Support\Facades\Mail;

class SendInternetShopReportService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function send($content, $to, $subject)
    {
        Mail::to($to)->send(new InternetShopReport($content, config('mail.from.address'), $subject));

        return response('success', 200);
    }
}
