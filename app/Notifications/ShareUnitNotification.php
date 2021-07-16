<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShareUnitNotification extends Notification
{
    use Queueable;

    public $link;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($link, $message)
    {
        $this->link = $link;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
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
