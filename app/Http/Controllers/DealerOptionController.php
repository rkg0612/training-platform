<?php

namespace App\Http\Controllers;

use App\Services\DealerOptionService;
use Illuminate\Http\Request;

class DealerOptionController extends Controller
{
    public $dealerOptionService;

    public function __construct(DealerOptionService $dealerOptionService)
    {
        $this->dealerOptionService = $dealerOptionService;
    }

    public function index(Request $request)
    {
        return $this->dealerOptionService->index($request->get('email'));
    }
}
