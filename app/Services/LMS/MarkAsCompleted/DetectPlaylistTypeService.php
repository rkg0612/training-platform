<?php

namespace App\Services\LMS\MarkAsCompleted;

use App\Models\AssignedModule;
use App\Models\AssignedPlaylist;
use App\Models\Playlist;

class DetectPlaylistTypeService
{
    private $user;

    const SHARED_PLAYLIST = 'shared';
    const ASSIGNED_PLAYLIST = 'assigned';
    const OWN_PLAYLIST = 'owner';

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function scan($playlistId)
    {
        if ($this->checkInSharedPlaylist($playlistId)) {
            return self::SHARED_PLAYLIST;
        }

        if ($this->checkedInAssignedPlaylist($playlistId)) {
            return self::ASSIGNED_PLAYLIST;
        }

        if ($this->isOwnPlaylist($playlistId)) {
            return self::OWN_PLAYLIST;
        }

        abort(404, 'Invalid playlist id');
    }

    /**
     * This one goes to user_units.
     * @param $playlistId
     * @return int
     */
    public function checkInSharedPlaylist($playlistId)
    {
        return AssignedPlaylist::where('assignee_id', $this->user->id)
            ->where('playlist_id', $playlistId)
            ->whereNotNull('shared_by')
            ->count();
    }

    /**
     * this one goes to assigned_playlist.
     * @param $playlistId
     * @return int
     */
    public function checkedInAssignedPlaylist($playlistId)
    {
        return AssignedPlaylist::where('assignee_id', $this->user->id)
            ->where('playlist_id', $playlistId)
            ->count();
    }

    public function isOwnPlaylist($playlistId)
    {
        return Playlist::where('id', $playlistId)
            ->where('user_id', $this->user->id)
            ->count();
    }
}
