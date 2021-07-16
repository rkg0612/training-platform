<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Services\Twilio\IncomingCallService;
use Illuminate\Http\Request;

class IncomingCallController extends Controller
{
    public $incomingCallService;

    public function __construct(IncomingCallService $incomingCallService)
    {
        $this->incomingCallService = $incomingCallService;
    }

    public function index(Request $request)
    {
        return $this->incomingCallService->answer($request);
    }
}
