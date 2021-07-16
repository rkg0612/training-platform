<?php

namespace App\Http\Controllers;

use App\Services\LMS\UserModuleService;
use App\Services\LMS\UserUnitService;
use Illuminate\Http\Request;

class MarkPlayedController extends Controller
{
    private $userunitservice;
    private $usermoduleservice;

    public function __construct(UserUnitService $userunitservice, UserModuleService $usermoduleservice)
    {
        $this->userunitservice = $userunitservice;
        $this->usermoduleservice = $usermoduleservice;
    }

    public function setUnitVideoPlayed(Request $request)
    {
        return $this->userunitservice->setPlayedUnit($request);
    }

    public function setPlayedModuleVideo(Request $request)
    {
        return $this->usermoduleservice->setPlayedModuleVideo($request);
    }
}
