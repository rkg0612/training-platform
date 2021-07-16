<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserMailForAdmins extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $specificDealer;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $specificDealer, $manager)
    {
        $this->user = $user;
        $this->specificDealer = $specificDealer;
        $this->manager = $manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mailers.new-user')->subject('Heads Up! New User Registered.');
    }
}
