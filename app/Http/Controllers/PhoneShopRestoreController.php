<?php

namespace App\Http\Controllers;

use App\Services\PhoneShop\PhoneShopRestoreService;
use Illuminate\Http\Request;

class PhoneShopRestoreController extends Controller
{
    private $phoneShopRestoreService;

    public function __construct(PhoneShopRestoreService $phoneShopRestoreService)
    {
        $this->phoneShopRestoreService = $phoneShopRestoreService;
    }

    public function restore($id)
    {
        return $this->phoneShopRestoreService->restore($id);
    }
}
