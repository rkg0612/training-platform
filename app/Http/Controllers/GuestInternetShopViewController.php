<?php

namespace App\Http\Controllers;

use App\Services\InternetShop\GuestInternetShopService;
use Illuminate\Http\Request;

class GuestInternetShopViewController extends Controller
{
    public $guestInternetShopService;

    public function __construct(GuestInternetShopService $guestInternetShopService)
    {
        $this->guestInternetShopService = $guestInternetShopService;
    }

    public function show($id)
    {
        return $this->guestInternetShopService->show($id);
    }
}
