<?php

namespace App\Services;

use App\Models\VoiceMail;

class VoiceMailService
{
    const FILE_TYPE_ID = 1;

    private $voiceMail;

    public function __construct(VoiceMail $voiceMail)
    {
        $this->voiceMail = $voiceMail;
    }

    public function index()
    {
        return response()->json($this->voiceMail->all());
    }
}
