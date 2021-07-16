<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class LMSAssignedPlaylistNotification extends Mailable
{
    public $playlists;
    public $dueDate;
    public $name;

    public function __construct($playlists, $dueDate, $name)
    {
        $this->playlists = $playlists;
        $this->dueDate = $dueDate;
        $this->name = $name;
    }

    public function build()
    {
        return $this->view('mailers.lms-assign-notification')
            ->subject('LMS Assigned Playlist Notification')
            ->with([
                'playlists' => $this->playlists,
                'due_date' => $this->dueDate,
                'name' => $this->name,
            ]);
    }
}
