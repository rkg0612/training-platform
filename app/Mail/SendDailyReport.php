<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendDailyReport extends Mailable
{
    public $name;
    public $link;
    public $fileName;

    public function __construct($name, $link, $fileName)
    {
        $this->name = $name;
        $this->link = $link;
        $this->fileName = $fileName;
    }

    public function build()
    {
        return $this->view('mailers.lms-usage-report')
                ->subject('LMS Usage Report - '.now()->format('m-d-Y'))
                ->attachFromStorageDisk('s3', $this->fileName)
                ->with([
                    'name' => $this->name,
                    'link' => $this->link,
                ]);
    }
}
