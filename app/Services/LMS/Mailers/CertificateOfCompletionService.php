<?php

namespace App\Services\LMS\Mailers;

use App\Mail\CertificateOfCompletionMail;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CertificateOfCompletionService
{
    public function send($user, $series, $category)
    {
        $params = [
            'salesperson' => $user->name,
            'completedSeriesName' => $series,
            'category' => $category,
        ];

        $fileName = $this->createPDF($user, $params);
        if (Mail::to($user->email)->send(new CertificateOfCompletionMail($params, $fileName))) {
            return true;
        }
    }

    public function createPDF($user, $params)
    {
        $params = [
            'salesperson' => $user->name,
            'completedSeriesName' => $params['completedSeriesName'],
            'manager' => '',
            'dealer' => $user->dealer->name,
            'jobTitle' => $user->job_title,
            'image' => Storage::disk('s3')->url('mailer/certificate-background.png'),
        ];

        $pdf = PDF::loadView('certificates.completion', $params)
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $fileName = '/certificates/'.$user->id.'_'.$user->name.'_'.Carbon::now()->timestamp.'.pdf';

        Storage::disk('s3')->put($fileName, $pdf->output());

        return $fileName;
    }
}
