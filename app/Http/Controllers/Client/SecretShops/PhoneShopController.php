<?php

namespace App\Http\Controllers\Client\SecretShops;

use App\Http\Controllers\Controller;
use App\Services\PhoneShop\Client\PhoneShopService;
use Illuminate\Http\Request;

class PhoneShopController extends Controller
{
    private $phoneShopService;

    public function __construct(PhoneShopService $phoneShopService)
    {
        $this->phoneShopService = $phoneShopService;
    }

    public function index(Request $request)
    {
        return $this->phoneShopService->index($request);
    }
}
