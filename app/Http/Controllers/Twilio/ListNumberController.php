<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Services\Twilio\ListNumberService;
use Illuminate\Http\Request;

class ListNumberController extends Controller
{
    private $listNumberService;

    public function __construct(ListNumberService $listNumberService)
    {
        $this->listNumberService = $listNumberService;
    }

    public function __invoke(Request $request)
    {
        return $this->listNumberService->show($request, 5);
    }
}
