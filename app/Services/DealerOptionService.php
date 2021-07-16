<?php

namespace App\Services;

use App\Models\DealerOption;
use App\Models\User;

class DealerOptionService
{
    public $dealerOption;
    public $user;

    public function __construct(DealerOption $dealerOption, User $user)
    {
        $this->dealerOption = $dealerOption;
        $this->user = $user;
    }

    public function index($email)
    {
        return $this->dealerOption
            ->select(['name', 'value', 'type'])
            ->where('dealer_id', $this->user->where('email', $email)->first()->dealer_id)
            ->get()
            ->toArray();
    }
}
