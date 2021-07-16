<?php

namespace App\Services\LMS\RelatedUnit;

use App\Models\AssignedPlaylist;
use App\Models\AssignedPlaylistUnit;
use App\Models\Playlist;
use App\Models\UserUnit;

class PlaylistRelatedUnitsService
{
    public function show($request)
    {
        return $this->fetchPlaylist($request['playlistId']);
    }

    public function fetchPlaylist($playlistId)
    {
        $playlist = Playlist::with('units')
            ->where('id', $playlistId)
            ->get();

        return $playlist->map(function ($playlist) {
            return [
                'id' => $playlist->id,
                'name' => $playlist->name,
                'units' =>  $playlist->units->map(function ($unit) use ($playlist) {
                    return [
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'is_completed' => $this->isCompleted($unit->id, $playlist->id),
                    ];
                }),
            ];
        });
    }

    public function isCompleted($unitId, $playlistId)
    {
        // check if owner
        if (Playlist::where('id', $playlistId)->where('user_id', auth()->user()->id)->exists()) {
            return UserUnit::where('user_id', auth()->user()->id)
                ->where('unit_id', $unitId)
                ->whereNotNull('date_completed')
                ->exists();
        }

        return AssignedPlaylist::whereHas('assignedPlaylistUnit', function ($query) use ($unitId) {
            $query->where('unit_id', $unitId)
                    ->whereNotNull('date_completed');
        })
            ->where('assignee_id', auth()->user()->id)
            ->exists();
    }
}
