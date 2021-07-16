<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class VideoOfTheDay extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $link;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param $message
     * @param $link
     */
    public function __construct($message, $link)
    {
        $this->message = $message;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
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
