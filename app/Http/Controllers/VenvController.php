<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VenvController extends Controller
{
    public function getAwsUrl()
    {
        return env('AWS_URL');
    }
}
