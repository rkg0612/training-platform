<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateOfCompletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $salesperson;
    public $completedSeriesName;
    public $category;
    public $fileName;

    public function __construct(array $information, string $fileName)
    {
        $this->salesperson = $information['salesperson'];
        $this->completedSeriesName = $information['completedSeriesName'];
        $this->category = $information['category'];
        $this->fileName = $fileName;
    }

    public function build()
    {
        return $this->view('mailers.lms-completion')
            ->with([
                'salesperson' => $this->salesperson,
                'completedSeriesName' => $this->completedSeriesName,
                'category' => $this->category,
            ])
            ->attachFromStorageDisk('s3', $this->fileName);
    }
}
