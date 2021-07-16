<?php

namespace App\Services\LMS\Mailers;

use App\Mail\ExpirationOfLmsAssigned;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LMSAssignedExpirationService
{
    public function __construct()
    {
        return $this->sendMails();
    }

    public function assignedUnits($userId)
    {
        $assignedUnits = DB::table('user_units')
            ->where('user_id', $userId)
            ->whereNotNull('date_completed')
            ->where('due_date', now()->tomorrow())
            ->get();

        return $assignedUnits->map(function ($assignedUnit) {
            return [
                'id' => $assignedUnit->unit_id,
                'name' => DB::table('units')
                    ->select('name')
                    ->where('id', $assignedUnit->unit_id)
                    ->first()->name,
            ];
        });
    }

    public function assignedModules($modules, $userId)
    {
        return $modules->map(function ($module) use ($userId) {
            $units = DB::table('units')->where('module_id', $module->id);
            $completedUnits = DB::table('user_units')
                ->whereIn('unit_id', $units->pluck('id')->toArray())
                ->whereNotNull('date_completed')
                ->where('user_id', $userId)
                ->count();

            $notCompleted = $units->count() - $completedUnits;

            if ($notCompleted) {
                $module = DB::table('modules')
                    ->where('id', $module->module_id)
                    ->first();

                return [
                    'id' => $module->id,
                    'name' => $module->name,
                ];
            }
        });
    }

    public function assignedPlaylists($assignedPlaylists, $userId)
    {
        return $assignedPlaylists->map(function ($assignedPlaylist) {
            $units = DB::table('playlist_unit')
                ->where('playlist_id', $assignedPlaylist->playlist_id)
                ->get();
            $completedUnits = DB::table('assigned_playlist_units')
                ->whereIn('unit_id', $units->pluck('id')->toArray())
                ->whereNotNull('date_completed')
                ->where('assigned_playlist_id', $assignedPlaylist->id)
                ->count();

            $notCompleted = $units->count() - $completedUnits;

            if ($notCompleted) {
                return [
                    'id' => $assignedPlaylist->playlist_id,
                    'name' => Playlist::find($assignedPlaylist->playlist_id)->name,
                ];
            }
        });
    }

    public function sendMails()
    {
        $users = User::whereHas('myAssignedModules')
            ->orWhereHas('myAssignedPlaylists')
            ->orWhereHas('assignedUnits')
            ->with([
                'myAssignedModules' => function ($query) {
                    $query->where('due_date', now()->tomorrow());
                },
                'myAssignedPlaylists' => function ($query) {
                    $query->where('due_date', now()->tomorrow());
                },
            ])
            ->get();

        $users = $users->map(function ($user) {
            $assignedModules = $this->assignedModules($user->myAssignedModules, $user->id);
            $assignedPlaylists = $this->assignedPlaylists($user->myAssignedPlaylists, $user->id);
            $assignedUnits = $this->assignedUnits($user->id);

            return collect([
                'name' => $user->name,
                'email' => $user->email,
                'assignedUnits' => $assignedUnits,
                'assignedModules' => $assignedModules,
                'assignedPlaylists' => $assignedPlaylists,
            ]);
        });

        $users->each(function ($user) {
            if ($user->get('assignedUnits')->count() ||
                $user->get('assignedModules')->count() ||
                $user->get('assignedPlaylists')->count()) {
                Mail::to($user->get('email'))->send(
                    new ExpirationOfLmsAssigned(
                        $user->get('name'),
                        now()
                            ->tomorrow()
                            ->format('m-d-Y'),
                        $user->get('assignedUnits')->unique(),
                        $user->get('assignedModules')->unique(),
                        $user->get('assignedPlaylists')->unique()
                    )
                );
            }
        });
    }
}
