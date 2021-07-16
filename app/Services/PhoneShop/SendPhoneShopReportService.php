<?php

namespace App\Services\PhoneShop;

use App\Mail\PhoneShopReport;
use App\Models\PhoneShop;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;

class SendPhoneShopReportService
{
    public $phoneShop;

    public function __construct(PhoneShop $phoneShop)
    {
        $this->phoneShop = $phoneShop;
    }

    public function send($content, $to, $subject)
    {
        Mail::to($to)->send(new PhoneShopReport($content, config('mail.from.address'), $subject));

        return response('success', 200);
    }
}
