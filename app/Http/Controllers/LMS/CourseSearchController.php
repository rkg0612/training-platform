<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        if (isset($request->searchKeyword)) {
            return response(Course::searchByValue($request->searchKeyword));
        }
        abort(404);
    }
}
