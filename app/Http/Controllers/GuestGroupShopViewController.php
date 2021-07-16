<?php

namespace App\Http\Controllers;

use App\Services\GuestGroupShopViewService;

class GuestGroupShopViewController extends Controller
{
    public $guestGroupShopViewService;

    public function __construct(GuestGroupShopViewService $guestGroupShopViewService)
    {
        $this->guestGroupShopViewService = $guestGroupShopViewService;
    }

    public function show($id)
    {
        return $this->guestGroupShopViewService->show($id);
    }
}
