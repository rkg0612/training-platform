<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Services\Twilio\IncomingSMSService;
use Illuminate\Http\Request;

class IncomingSMSController extends Controller
{
    public $incomingSmsService;

    public function __construct(IncomingSMSService $incomingSmsService)
    {
        $this->incomingSmsService = $incomingSmsService;
    }

    public function index(Request $request)
    {
        return $this->incomingSmsService->index($request->all());
    }
}
