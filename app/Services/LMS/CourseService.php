<?php

namespace App\Services\LMS;

use App\Http\Resources\CourseCollection;
use App\Models\Course;
use Facades\App\Services\LMS\CourseDealerService;
use Illuminate\Support\Collection;

class CourseService
{
    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $status = $request->type;
        $search = $request->search;

        $course = Course::latest();

        if (! empty($search)) {
            $course->where('name', 'like', '%'.$search.'%');
        }

        if ($status == 'inactive') {
            $course->onlyTrashed();
        } elseif ($status == 'all') {
            $course->withTrashed();
        }

        return $course->paginate($perPage);
    }

    public function store($request)
    {
        $course = new Course;
        $course->name = $request->name;
        $course->save();

        $course->dealers()->sync($request->assigned_dealers);

        return response()->json([
            $course,
        ]);
    }

    public function update($request)
    {
        $course = Course::findOrFail($request->id);
        $course->name = $request->name;
        $course->save();

        $course->dealers()->sync($request->assigned_dealers);

        return response()->json([
            $course,
        ]);
    }

    public function delete($id)
    {
        if (Course::find($id)->delete()) {
            return response('success');
        }

        return abort(404);
    }

    public function restore($id)
    {
        $course = Course::onlyTrashed()->get()->find($id);
        $course->restore();

        return response()->json(
            $course
        );
    }
}
