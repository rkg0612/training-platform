<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class LMSAssignedModuleNotification extends Mailable
{
    use Queueable;

    public $modules;
    public $dueDate;
    public $name;

    public function __construct($modules, $dueDate, $name)
    {
        $this->modules = $modules;
        $this->dueDate = $dueDate;
        $this->name = $name;
    }

    public function build()
    {
        return $this->view('mailers.lms-assign-notification')
            ->subject('LMS Assigned Module Notification')
            ->with([
                'modules' => $this->modules,
                'due_date' => $this->dueDate,
                'name' => $this->name,
            ]);
    }
}
