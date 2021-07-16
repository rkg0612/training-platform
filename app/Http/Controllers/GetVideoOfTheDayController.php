<?php

namespace App\Http\Controllers;

use App\Services\GetVideoOfTheDayService;
use Illuminate\Http\Request;

class GetVideoOfTheDayController extends Controller
{
    public $getVideoOfTheDayService;

    public function __construct(GetVideoOfTheDayService $getVideoOfTheDayService)
    {
        $this->getVideoOfTheDayService = $getVideoOfTheDayService;
    }

    public function index()
    {
        return $this->getVideoOfTheDayService->index();
    }
}
