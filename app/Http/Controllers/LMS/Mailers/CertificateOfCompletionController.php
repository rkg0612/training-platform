<?php

namespace App\Http\Controllers\LMS\Mailers;

use App\Http\Controllers\Controller;
use App\Services\LMS\Mailers\CertificateOfCompletionService;
use Illuminate\Http\Request;

class CertificateOfCompletionController extends Controller
{
    public $certificateOfCompletionService;

    public function __construct(CertificateOfCompletionService $certificateOfCompletionService)
    {
        $this->certificateOfCompletionService = $certificateOfCompletionService;
    }

    public function index(Request $request)
    {
        return $this->certificateOfCompletionService->send($request);
    }
}
