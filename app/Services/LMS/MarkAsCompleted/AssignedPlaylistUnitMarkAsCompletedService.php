<?php

namespace App\Services\LMS\MarkAsCompleted;

use App\Models\AssignedPlaylist;
use App\Models\AssignedPlaylistUnit;
use Carbon\Carbon;

class AssignedPlaylistUnitMarkAsCompletedService
{
    public function markAsCompleted($request)
    {
        $this->playlistId = $request['playlistId'];

        $assignedPlaylist = AssignedPlaylist::query()
            ->where('playlist_id', $this->playlistId)
            ->where('assignee_id', auth()->user()->id)
            ->whereHas('assignedPlaylistUnit', function ($query) use ($request) {
                return $query->where('unit_id', $request['unitId']);
            })
            ->exists();
        $playlist = AssignedPlaylist::query()
            ->where('playlist_id', $this->playlistId)
            ->where('assignee_id', auth()->user()->id)
            ->first();
        if (! $assignedPlaylist) {
            $assignedPlaylistUnit = new AssignedPlaylistUnit;
            $assignedPlaylistUnit->unit_id = $request['unitId'];
            $assignedPlaylistUnit->assigned_playlist_id = $playlist->id;
        } else {
            $assignedPlaylistUnit = AssignedPlaylistUnit::where('assigned_playlist_id', $playlist->id)
                ->where('unit_id', $request['unitId'])
                ->first();
        }
        $assignedPlaylistUnit->date_completed = Carbon::now();
        $assignedPlaylistUnit->save();

        return $assignedPlaylistUnit;
    }

    public function isCompleted($request)
    {
        return AssignedPlaylist::where('assignee_id', auth()->user()->id)
            ->whereHas('assignedPlaylistUnit', function ($query) use ($request) {
                $query->where('id', $request['unitId'])->whereNotNull('date_completed');
            })->count();
    }
}
