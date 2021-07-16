<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupShopReport extends Mailable
{
    use Queueable, SerializesModels;

    private $content;
    private $info;

    /**
     * Create a new message instance.
     *
     * @param $content
     * @param $to
     * @param $subject
     */
    public function __construct($content, $to, $subject)
    {
        $this->content = $content;
        $this->info = [
            'to' => $to,
            'subject' => $subject,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->html($this->content)
            ->to($this->info['to'])
            ->subject($this->info['subject']);
    }
}
