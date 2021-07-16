<?php

namespace App\Http\Controllers\Client\SecretShops;

use App\Http\Controllers\Controller;
use App\Services\InternetShop\Client\InternetShopService;
use Illuminate\Http\Request;

class InternetShopController extends Controller
{
    private $internetShopService;

    public function __construct(InternetShopService $internetShopService)
    {
        $this->internetShopService = $internetShopService;
    }

    public function index(Request $request)
    {
        return $this->internetShopService->index($request);
    }
}
