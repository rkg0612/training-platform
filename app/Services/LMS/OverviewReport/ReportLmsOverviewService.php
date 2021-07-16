<?php

namespace App\Services\LMS\OverviewReport;

use App\Models\Role;
use App\Models\UserLoggedInLog;
use App\Models\UserUnit;
use App\Models\UserWatchedFeaturedVideo;

class ReportLmsOverviewService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index()
    {
        if (
            $this->user->roles->contains('name', Role::SECRET_SHOPPER) ||
            $this->user->roles->contains('name', Role::SALESPERSON)
        ) {
            abort(403);
        }

        $users = $this->getUsers();
        $userIds = $users->pluck('id');

        return [
            'mostUnitsCompleted' => $this->getUsersStatistics('mostUnitsCompleted', $userIds->toArray()),
            'mostWatchedVideoOfTheDay' => $this->getUsersStatistics('mostWatchedVideoOfTheDay', $userIds->toArray()),
            'mostDaysVisited' => $this->getUsersStatistics('mostDaysVisited', $userIds->toArray()),
            'mostVideoWatched' => $this->getUsersStatistics('mostVideoWatched', $userIds->toArray()),
        ];
    }

    /**
     * Get all users based on the manager's role.
     */
    private function getUsers()
    {
        $dealer = $this->user->dealer;

        if ($this->user->dealer->is_automotive == 0) {
            return $dealer->users()->get();
        }

        if (
            $this->user->roles->contains('name', Role::ACCOUNT_MANAGER) ||
            $this->user->roles->contains('name', Role::SUPER_ADMINISTRATOR)
        ) {
            return $dealer->users()->get();
        }

        return $this->user->specificDealer->users()->get();
    }

    private function getUsersStatistics($type, array $userIds)
    {
        $visitedFromDays = 30;
        $userUnits = UserUnit::with(['user'])
            ->whereIn('user_id', $userIds)
            ->get();

        if ($type === 'mostUnitsCompleted') {
            $units = $userUnits->filter(function ($item) {
                return $item->date_completed !== null;
            });
        } elseif ($type === 'mostWatchedVideoOfTheDay') {
            $units = UserWatchedFeaturedVideo::with(['user'])
                ->whereIn('user_id', $userIds)
                ->where('watched', 1)
                ->get();
        } elseif ($type === 'mostVideoWatched') {
            $units = $userUnits->filter(function ($item) {
                return $item->played === 1;
            });
        } else {
            $units = UserLoggedInLog::with(['user'])
                ->whereIn('user_id', $userIds)
                ->whereBetween('created_at', [now()->subDays($visitedFromDays), now()])
                ->get();
        }

        if ($units->isEmpty()) {
            return $units;
        }

        $lowCount = 5;
        $result = $units->groupBy('user_id')
            ->transform(function ($item) use ($lowCount) {
                if ($item->count() < $lowCount) {
                    return 'skip';
                }

                $user = $item->first()->user;
                $user->count = $item->count();

                return $user;
            })
            ->filter(function ($item) {
                return $item !== 'skip';
            })
            ->sortByDesc('count');

        return $result->values();
    }
}
