<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedModuleNotification extends Notification
{
    use Queueable;

    public $link;
    public $message;
    public $name;
    public $dueDate;
    public $modules;
    public $subject;
    public $mod_for;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($link, $message, $modules, $dueDate, $name, $subject, $mod_for)
    {
        $this->link = $link;
        $this->message = $message;
        $this->modules = $modules;
        $this->dueDate = $dueDate;
        $this->name = $name;
        $this->subject = $subject;
        $this->mod_for = $mod_for;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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

    public function toMail($notifiable)
    {
        $_message = (new MailMessage)
            ->subject($this->subject);

        $_message->markdown('mailers.lms-assign-notification', [
            $this->mod_for => $this->modules,
            'dueDate' => $this->dueDate,
            'name' => $this->name,
        ]);

        return $_message;
    }
}
