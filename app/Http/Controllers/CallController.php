<?php

namespace App\Http\Controllers;

use App\Services\CallService;
use Illuminate\Http\Request;

class CallController extends Controller
{
    protected $callService;

    public function __construct(CallService $callService)
    {
        $this->callService = $callService;
    }

    public function destroy($id)
    {
        return $this->callService->destroy($id);
    }
}
