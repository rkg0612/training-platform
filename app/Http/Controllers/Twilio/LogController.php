<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Services\Twilio\GetLogsService;
use Illuminate\Http\Request;

class LogController extends Controller
{
    private $getLogsService;

    public function __construct(GetLogsService $getLogsService)
    {
        $this->getLogsService = $getLogsService;
    }

    public function __invoke(Request $request)
    {
        return $this->getLogsService->show($request);
    }
}
