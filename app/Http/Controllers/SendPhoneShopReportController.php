<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneShop\SendReport;
use App\Services\PhoneShop\SendPhoneShopReportService;
use Illuminate\Http\Request;

class SendPhoneShopReportController extends Controller
{
    public $sendPhoneShopReportService;

    public function __construct(SendPhoneShopReportService $phoneShopReportService)
    {
        $this->sendPhoneShopReportService = $phoneShopReportService;
    }

    public function store(SendReport $request)
    {
        return $this->sendPhoneShopReportService->send(
            $request->get('content'),
            $request->get('to'),
            $request->get('subject')
        );
    }
}
