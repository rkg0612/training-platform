<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerCompletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $salesperson;
    public $manager;
    public $completedSeriesName;
    public $category;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($manager, $salesperson, $completedSeriesName, $category)
    {
        $this->manager = $manager;
        $this->salesperson = $salesperson;
        $this->completedSeriesName = $completedSeriesName;
        $this->category = $category;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mailers.lms-manager-completion')
            ->with([
                'manager' => $this->manager,
                'salesperson' => $this->salesperson,
                'completedSeriesName' => $this->completedSeriesName,
                'category' => $this->category,
            ]);
    }
}
