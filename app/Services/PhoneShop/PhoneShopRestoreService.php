<?php

namespace App\Services\PhoneShop;

use App\Models\PhoneShop;

class PhoneShopRestoreService
{
    private $phoneShop;

    public function __construct(PhoneShop $phoneShop)
    {
        $this->phoneShop = $phoneShop;
    }

    public function restore($id)
    {
        if ($this->phoneShop->onlyTrashed()->find($id)->restore()) {
            return [
                'success' => true,
            ];
        }
    }
}
