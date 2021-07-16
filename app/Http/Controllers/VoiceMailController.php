<?php

namespace App\Http\Controllers;

use App\Services\VoiceMailService;

class VoiceMailController extends Controller
{
    // For now i just declared the fetching of voice mails.

    private $voiceMailService;

    public function __construct(VoiceMailService $voiceMailService)
    {
        $this->voiceMailService = $voiceMailService;
    }

    public function __invoke()
    {
        return $this->voiceMailService->index();
    }
}
