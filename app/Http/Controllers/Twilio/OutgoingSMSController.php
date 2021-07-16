<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutgoingSMSRequest;
use App\Services\Twilio\OutgoingSMSService;
use Illuminate\Http\Request;

class OutgoingSMSController extends Controller
{
    public $outgoingSMSService;

    public function __construct(OutgoingSMSService $outgoingSMSService)
    {
        $this->outgoingSMSService = $outgoingSMSService;
    }

    public function store(OutgoingSMSRequest $request)
    {
        return $this->outgoingSMSService->send($request->validated());
    }
}
