<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCalendar extends Notification
{
    use Queueable;

    private $message;
    private $link;

    public function __construct($message, $link)
    {
        $this->message = $message;
        $this->link = $link;
    }

    public function via()
    {
        return ['database', 'broadcast'];
    }

    public function toArray()
    {
        return [
            'message' => $this->message,
            'link' => $this->link,
        ];
    }

    public function toBroadcast()
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'link' => $this->link,
        ]);
    }
}
