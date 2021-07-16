<?php

namespace App\Http\Controllers;

use App\Services\LMS\GetUserByRolesService;
use Illuminate\Http\Request;

class GetUserByRolesController extends Controller
{
    private $getUserByRolesService;

    public function __construct(GetUserByRolesService $getUserByRolesService)
    {
        $this->getUserByRolesService = $getUserByRolesService;
    }

    public function index(Request $request)
    {
        return $this->getUserByRolesService->get($request->get('roles'));
    }
}
