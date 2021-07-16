<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use Facades\App\Services\LMS\ShareService;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function shareUnit(Request $request)
    {
        return ShareService::shareUnit($request);
    }

    public function shareModule(Request $request)
    {
        return ShareService::shareModule($request);
    }
}
