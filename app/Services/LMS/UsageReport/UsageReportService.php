<?php

namespace App\Services\LMS\UsageReport;

use App\Models\AssignedPlaylist;
use App\Models\AssignedPlaylistUnit;
use App\Models\Module;
use App\Models\Playlist;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnit;
use App\Models\UserWatchedFeaturedVideo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UsageReportService
{
    public function moduleProgress($user, $moduleId)
    {
        $totalUnits = DB::table('units')->where('module_id', $moduleId);

        $completedUnits = DB::table('user_units')
            ->whereIn('unit_id', $totalUnits->pluck('id')->toArray())
            ->whereNotNull('date_completed')
            ->where('user_id', $user->id)
            ->count();

        return number_format(($completedUnits / $totalUnits->count()) * 100, 2).'%';
    }

    public function playlistProgress($playlistId)
    {
        $playlistUnits = DB::table('playlist_unit')
            ->where('playlist_id', '=', $playlistId)
            ->get();

        $completedUnits = AssignedPlaylistUnit::where(
            'assigned_playlist_id',
            $playlistId
        )
            ->whereNotNull('date_completed')
            ->get();

        $totalCompletedUnits = $playlistUnits
            ->filter(function ($playlistUnit) use ($completedUnits) {
                return $completedUnits->contains('unit_id', $playlistUnit->unit_id);
            })
            ->count();

        return number_format(($totalCompletedUnits / $playlistUnits->count()) * 100, 2).'%';
    }

    public function getLastVideoCompleted($userId)
    {
        $unitId = UserUnit::where('user_id', $userId)
            ->whereNotNull('date_completed');

        if (! $unitId->exists()) {
            return;
        }

        return DB::table('units')
            ->where('id', $unitId->latest()->first()->unit_id)
            ->select('name')->first()->name;
    }

    public function getLastVideoWatched($userId)
    {
        $userUnit = UserUnit::where('user_id', $userId)
            ->whereNotNull('date_completed');

        if (! $userUnit->exists()) {
            return '';
        }

        return $userUnit->latest()->first()->date_completed->format('m-d-Y');
    }

    public function getLastVideoPlayed($userId)
    {
        $userUnit = UserUnit::where('user_id', $userId)
            ->where('played', true);

        if (! $userUnit->exists()) {
            return '';
        }

        $userUnit = $userUnit->latest()->first();

        return $userUnit->unit->name.' - '.$userUnit->created_at->format('m-d-Y');
    }

    public function getLastModuleVideoPlayed($userId)
    {
        $moduleUser = DB::table('module_user')
            ->where('user_id', $userId)
            ->where('played', true);

        if (! $moduleUser->exists()) {
            return '';
        }

        $module = Module::where('id', $moduleUser->latest()->first()->module_id)
            ->first();

        if (empty($module)) {
            return null;
        }

        return $module->name;
    }

    public function getTotalVideosPlayed($userId)
    {
        $userUnits = UserUnit::where('user_id', $userId)
            ->where('played', true)
            ->count();

        $modulesPlayed = DB::table('module_user')
            ->where('user_id', $userId)
            ->where('played', true)
            ->distinct()
            ->count('module_id');

        return intval($userUnits) + intval($modulesPlayed);
    }

    public function getLatestIntroVideoWatched($userId)
    {
        $userModule = User::findOrFail($userId)->modules()->wherePivot('intro_video_watched', 1)->latest('id')->get();

        if ($userModule->isEmpty()) {
            return '';
        }

        return $userModule->first()->name;
    }

    public function generateAssignedModulesList($assignedModules, $user)
    {
        return ! is_null($assignedModules) ? $assignedModules->map(function ($module) use ($user) {
            return collect([
                'name' => DB::table('modules')
                    ->where('id', $module->module_id)
                    ->pluck('name')[0],
                'progress' => $this->moduleProgress($user, $module->module_id),
            ]);
        }) : [];
    }

    public function generateAssignedPlaylist($assignedPlaylist, $user)
    {
        return ! is_null($assignedPlaylist) ? $assignedPlaylist->map(function ($playlist) {
            return collect([
                'name' => Playlist::where('id', $playlist->playlist_id)->first()->name,
                'progress' => $this->playlistProgress($playlist->playlist_id),
            ]);
        })
        : [];
    }

    public function getNumberOfUnitsCompleted($userId)
    {
        $userUnitsCompleted = UserUnit::where('user_id', $userId)
            ->whereBetween('date_completed', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $assignedPlaylistIds = AssignedPlaylist::where('assignee_id', $userId)
            ->pluck('id')->toArray();

        $completedPlaylistUnits = AssignedPlaylistUnit::whereIn('assigned_playlist_id', $assignedPlaylistIds)
            ->whereBetween('date_completed', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->count();

        return $userUnitsCompleted + $completedPlaylistUnits;
    }

    public function getDetailsOfLastWatchedVideoOfTheDay($userId)
    {
        $watchedVideo = UserWatchedFeaturedVideo::with(['featuredVideo'])
            ->where('user_id', $userId)
            ->where('watched', true)
            ->latest()
            ->take(1)
            ->first();

        if (empty($watchedVideo->featuredVideo)) {
            return null;
        }

        return $watchedVideo;
    }

    public function getTotalUnitsCompleted($userId)
    {
        $userUnitsCompleted = UserUnit::where('user_id', $userId)
            ->whereNotNull('date_completed')
            ->count();

        $assignedPlaylistIds = AssignedPlaylist::where('assignee_id', $userId)
            ->pluck('id')->toArray();

        $completedPlaylistUnits = AssignedPlaylistUnit::whereIn('assigned_playlist_id', $assignedPlaylistIds)
            ->whereNotNull('date_completed')
            ->count();

        return $userUnitsCompleted + $completedPlaylistUnits;
    }

    public function mapUsers(Collection $users)
    {
        return $users->map(function ($user) {
            $modulesList = $this->generateAssignedModulesList($user->myAssignedModules, $user);
            $playlist = $this->generateAssignedPlaylist($user->assignedPlaylist, $user);
            $lastWatchedVideoOfTheDay = $this->getDetailsOfLastWatchedVideoOfTheDay($user->id);

            return collect([
                'id' => $user->id,
                'name' => $user->name,
                'last_login_at' => ! is_null($user->last_login_at) ? $user->last_login_at->format('m-d-Y') : '',
                'last_video_played' => $this->getLastVideoPlayed($user->id),
                'last_module_video_played' => $this->getLastModuleVideoPlayed($user->id),
                'total_videos_played' => $this->getTotalVideosPlayed($user->id),
                'last_video_completed' => $this->getLastVideoCompleted($user->id),
                'last_video_completed_at' => $this->getLastVideoWatched($user->id),
                'last_intro_video_watched' => $this->getLatestIntroVideoWatched($user->id),
                'assignedModules' => $modulesList,
                'assignedPlaylists' => $playlist,
                'lastWatchedVideoOfTheDayTitle' => ! is_null($lastWatchedVideoOfTheDay) ? $lastWatchedVideoOfTheDay->featuredVideo->title : '',
                'lastWatchedVideoOfTheDayDate' => ! is_null($lastWatchedVideoOfTheDay) ? $lastWatchedVideoOfTheDay->created_at->format('m-d-Y') : '',
                'numberOfUnitsCompleted' => $this->getNumberOfUnitsCompleted($user->id),
                'totalNumberOfUnitsCompleted' => $this->getTotalUnitsCompleted($user->id),
            ]);
        })->sortBy('-last_video_completed_at');
    }

    public function mapAutomotiveUsers(Collection $dealers)
    {
        return $dealers->map(function ($users) {
            return $users->map(function ($user) {
                $modulesList = $this->generateAssignedModulesList($user->myAssignedModules, $user);
                $playlist = $this->generateAssignedPlaylist($user->assignedPlaylist, $user);
                $lastWatchedVideoOfTheDay = $this->getDetailsOfLastWatchedVideoOfTheDay($user->id);

                return collect([
                    'id' => $user->id,
                    'name' => $user->name,
                    'specific_dealer_id' => $user->specific_dealer_id,
                    'last_login_at' => ! is_null($user->last_login_at) ? $user->last_login_at->format('m-d-Y') : '',
                    'last_video_played' => $this->getLastVideoPlayed($user->id),
                    'last_module_video_played' => $this->getLastModuleVideoPlayed($user->id),
                    'total_videos_played' => $this->getTotalVideosPlayed($user->id),
                    'last_video_completed' => $this->getLastVideoCompleted($user->id),
                    'last_video_completed_at' => $this->getLastVideoWatched($user->id),
                    'last_intro_video_watched' => $this->getLatestIntroVideoWatched($user->id),
                    'assignedModules' => $modulesList,
                    'assignedPlaylists' => $playlist,
                    'lastWatchedVideoOfTheDayTitle' => ! is_null($lastWatchedVideoOfTheDay) ? $lastWatchedVideoOfTheDay->featuredVideo->title : '',
                    'lastWatchedVideoOfTheDayDate' => ! is_null($lastWatchedVideoOfTheDay) ? $lastWatchedVideoOfTheDay->created_at->format('m-d-Y') : '',
                    'numberOfUnitsCompleted' => $this->getNumberOfUnitsCompleted($user->id),
                    'totalNumberOfUnitsCompleted' => $this->getTotalUnitsCompleted($user->id),
                ])->all();
            })->sortBy('-last_video_completed_at')->toArray();
        });
    }
}
