<?php

namespace App\Services;

use App\Mail\GroupShopReport;
use App\Mail\GroupShopReportPreview;
use App\Mail\InternetShopReport;
use App\Models\InternetShop;
use Illuminate\Support\Facades\Mail;

class SendGroupShopReportService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function send($request)
    {
        Mail::to($request->to)
        ->send(new GroupShopReport($request->report, $request->to, $request->subject));

        return response('success', 200);
    }
}
