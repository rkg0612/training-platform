<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserUnit;
use Illuminate\Http\Request;

class PurgeDuplicateService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function purgeUserUnits()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            $userUnitIds = UserUnit::where('user_id', $user->id)
                ->select('unit_id')
                ->groupBy('unit_id')
                ->havingRaw("count('unit_id') > 1")
                ->get()
                ->pluck('unit_id');

            foreach ($userUnitIds as $id) {
                $unit = UserUnit::where('user_id', $user->id)
                    ->where('unit_id', $id)
                    ->latest('date_completed')
                    ->first();

                // Delete latest duplicate record assuming it's from the import
                $unit->delete();
                $count++;
            }
        }

        return $count;
    }
}
