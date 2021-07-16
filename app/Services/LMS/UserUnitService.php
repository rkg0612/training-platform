<?php

namespace App\Services\LMS;

use App\Models\AssignedPlaylist;
use App\Models\AssignedPlaylistUnit;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnit;
use Carbon\Carbon;

class UserUnitService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function setPlayedUnit($request)
    {
        $type = $request->type;
        if (! isset($type) || $type === null) {
            $type = 'default';
        }

        $unit = null;

        if ($type === 'playlist' || $type === 'assigned') {
            $assignedPlaylist = AssignedPlaylist::where('assignee_id', $this->user->id)
                ->whereHas('assignedPlaylistUnit', function ($query) use ($request) {
                    $query->where('id', $request->unit_id);
                })->first();
            if (! $assignedPlaylist) {
                $assignedPlaylist = AssignedPlaylist::query()
                    ->where('playlist_id', $request->playlistId)
                    ->where('assignee_id', auth()->user()->id)
                    ->first();

                $unit = new AssignedPlaylistUnit;
                $unit->unit_id = $request->unit_id;
                $unit->assigned_playlist_id = $assignedPlaylist->id;
                $unit->played = true;
                $unit->date_completed = null;
                $unit->save();
            } else {
                $unit = AssignedPlaylistUnit::where('assigned_playlist_id', $request->playlistId)
                    ->where('unit_id', $request->unit_id)
                    ->first();
                $unit->played = true;
                $unit->save();
            }
        } else {
            $unit = UserUnit::where('user_id', auth()->user()->id)
                ->where('unit_id', $request->unit_id)
                ->first();
            if (! $unit) {
                $unit = new UserUnit;
                $unit->user_id = $this->user->id;
                $unit->unit_id = $request->unit_id;
            }
            $unit->played = true;
            $unit->save();
        }

        return response()->json([
            'success'   =>  true,
            'unit'      =>  $unit,
        ], 200);
    }
}
