<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Services\Twilio\IncomingCallRecorderService;
use Illuminate\Http\Request;

class IncomingCallRecorderController extends Controller
{
    public $incomingCallRecorderService;

    public function __construct(IncomingCallRecorderService $incomingCallRecorderService)
    {
        $this->incomingCallRecorderService = $incomingCallRecorderService;
    }

    public function index(Request $request)
    {
        return $this->incomingCallRecorderService->index($request->all());
    }
}
