<?php

namespace App\Http\Controllers;

use App\Services\InternetShop\GroupShopService;

class GroupShopRestoreController extends Controller
{
    public $groupShopService;

    public function __construct(GroupShopService $groupShopService)
    {
        $this->groupShopService = $groupShopService;
    }

    public function update($id)
    {
        return $this->groupShopService->restore($id);
    }
}
