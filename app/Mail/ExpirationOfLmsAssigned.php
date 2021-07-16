<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpirationOfLmsAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $dateOfCompletion;
    public $name;
    public $modules;
    public $units;
    public $playlists;

    public function __construct(string $name, string $dateOfCompletion, $modules = null, $units = null, $playlists = null)
    {
        $this->name = $name;
        $this->dateOfCompletion = $dateOfCompletion;
        $this->modules = $modules;
        $this->units = $units;
        $this->playlists = $playlists;
    }

    public function build()
    {
        return $this->view('mailers.lms-assign-expiration')
            ->subject('LMS Assigned Units/Modules/Playlists Expiration Notice')
            ->with([
                'name' => $this->name,
                'dateOfCompletion' => $this->dateOfCompletion,
                'modules' => $this->modules,
                'units' => $this->units,
                'playlists' => $this->playlists,
            ]);
    }
}
