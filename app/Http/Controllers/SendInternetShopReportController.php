<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternetShop\SendReport;
use App\Services\InternetShop\SendInternetShopReportService;
use Illuminate\Http\Request;

class SendInternetShopReportController extends Controller
{
    public $sendInternetShopReportService;

    public function __construct(SendInternetShopReportService $sendInternetShopReportService)
    {
        $this->sendInternetShopReportService = $sendInternetShopReportService;
    }

    public function store(SendReport $request)
    {
        return $this->sendInternetShopReportService->send(
            $request->get('content'),
            $request->get('to'),
            $request->get('subject')
        );
    }
}
