<?php

namespace App\Http\Controllers;

use App\Services\PhoneShop\GuestPhoneShopService;
use Illuminate\Http\Request;

class GuestPhoneShopViewController extends Controller
{
    public $guestPhoneShopService;

    public function __construct(GuestPhoneShopService $guestPhoneShopService)
    {
        $this->guestPhoneShopService = $guestPhoneShopService;
    }

    public function show($id)
    {
        return $this->guestPhoneShopService->show($id);
    }

    public function store(Request $request)
    {
        return $this->guestPhoneShopService->store($request);
    }
}
