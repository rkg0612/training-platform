<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternetShopReport extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $sender;
    public $subj;

    /**
     * Create a new message instance.
     *
     * @param $template
     * @param $from
     * @param $subject
     */
    public function __construct($template, $from, $subject)
    {
        $this->template = $template;
        $this->sender = $from;
        $this->subj = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender)
            ->subject($this->subj)
            ->html($this->template);
    }
}
