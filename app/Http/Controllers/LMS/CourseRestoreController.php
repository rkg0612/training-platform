<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use Facades\App\Services\LMS\CourseService;
use Illuminate\Http\Request;

class CourseRestoreController extends Controller
{
    public function __invoke($id)
    {
        return CourseService::restore($id);
    }
}
