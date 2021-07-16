<?php

namespace App\Services;

use App\Models\Playlist;

class PlaylistService
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
        return Playlist::with([
            'units',
            'assignedPlaylist.user',
            'assignedPlaylist' => function ($query) {
                return $query->where('assignee_id', $this->user->id);
            },
        ])
            ->where('user_id', $this->user->id)
            ->orWhereHas('assignedPlaylist', function ($query) {
                return $query->where('assignee_id', $this->user->id);
            })
            ->get();
    }

    public function store($request)
    {
        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->user_id = $this->user->id;
        $playlist->save();

        $playlist->units()->attach($request->units);

        return $playlist->load([
            'units',
            'assignedPlaylist.user',
            'assignedPlaylist' => function ($query) {
                return $query->where('assignee_id', $this->user->id);
            },
        ]);
    }

    public function show($id)
    {
        $playlist = $this->findPlaylist($id);

        if (empty($playlist)) {
            abort(404);
        }

        $playlist->progress = $this->getProgress($playlist);

        return $playlist;
    }

    public function update($request, $id)
    {
        $playlist = $this->findPlaylist($id);

        $this->check($playlist);

        $playlist->{$request->type} = $request->value;
        $playlist->save();
    }

    public function destroy($id)
    {
        $playlist = $this->findPlaylist($id);

        $this->check($playlist);

        $playlist->delete();
    }

    private function check($playlist)
    {
        if (empty($playlist)) {
            abort('404', 'Playlist not found');
        }

        // if playlist doesn't belong to the requester
        // then abort the request
        if ($playlist->user_id != $this->user->id) {
            abort('403');
        }
    }

    private function findPlaylist($id)
    {
        return $playlist = Playlist::with([
            'units.module',
            'user',
            'assignedPlaylist.user',
            'assignedPlaylist.assignedPlaylistUnit',
            'assignedPlaylist' => function ($query) {
                return $query->where('assignee_id', $this->user->id);
            },
        ])
            ->where('id', $id)
            ->whereIn('user_id', $this->user->dealer->users->pluck('id')->toArray())
            ->first();
    }

    private function getProgress($playlist)
    {
        if ($playlist->assignedPlaylist->isEmpty()) {
            return;
        }

        $totalUnits = $playlist->units->count();
        $completedUnits = $this->getCompletedUnits($playlist);
        $totalCompletedUnits = ! $completedUnits->isEmpty() ? $completedUnits->count() : 0;

        if ($totalCompletedUnits < 1) {
            return 0;
        }

        return ($totalCompletedUnits / $totalUnits) * 100;
    }

    private function getCompletedUnits($playlist)
    {
        $completedUnits = $playlist->assignedPlaylist
            ->first()
            ->assignedPlaylistUnit;

        if ($completedUnits->isEmpty()) {
            return $completedUnits;
        }

        $result = collect();

        // check if the completed unit is still in the playlist
        foreach ($completedUnits as $completedUnit) {
            $check = Playlist::where('id', $playlist->id)
                ->whereHas('units', function ($query) use ($completedUnit) {
                    return $query->where('units.id', $completedUnit->unit_id);
                })
                ->first();

            if (empty($check)) {
                continue;
            }

            $result->push($completedUnit);
        }

        return $result;
    }
}
