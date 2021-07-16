<?php

namespace App\Services\LMS\Builder;

use App\Models\Category;
use App\Models\CategoryBuild;
use App\Models\Course;
use App\Models\CourseBuild;
use App\Models\CourseCategoryBuild;

class CourseBuilderService
{
    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $status = $request->type;
        $search = $request->search;
        $courseBuilds = CourseBuild::with(['category_builds']);

        if ($status == 'inactive') {
            $courseBuilds->onlyTrashed();
        } elseif ($status == 'all') {
            $courseBuilds->withTrashed();
        }

        if ($search) {
            $courseBuilds->where('name', 'LIKE', '%'.$search.'%');
        }

        if (! isset($request->sortBy)) {
            $courseBuilds->orderByDesc('created_at');
        }

        return $courseBuilds->paginate($perPage);
    }

    public function store($request)
    {
        $course_build = new CourseBuild;
        $course_build->fill($request->all());
        $course_build->save();

        $this->updateCategories($request->categories, $course_build);

        return response($course_build);
    }

    public function update($request)
    {
        $course_build = CourseBuild::findOrFail($request->id);
        $course_build->fill($request->all());
        $course_build->save();

        $this->updateCategories($request->categories, $course_build);

        return response($course_build);
    }

    private function updateCategories($categories, $course_build)
    {
        $course_build->category_builds()->forceDelete();
        foreach ($categories as $i => $category) {
            $course_build->category_builds()->create([
                'category_build_id' =>  $category,
                'course_build_id' =>  $course_build->id,
            ]);
        }
    }

    public function destroy($id)
    {
        if (CourseBuild::destroy($id)) {
            return response()->json([
                'success'   =>  true,
            ]);
        }
        abort(404);
    }

    public function restore($id)
    {
        $course_build = CourseBuild::onlyTrashed()->get()->find($id);
        $course_build->restore();

        return response()->json([
            $course_build,
        ]);
    }

    public function fetchCategoryBuildsByCourse($request)
    {
        $course_id = $request->course_id;
        $modules = CategoryBuild::with('course_build')
            ->whereHas('category.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })
            ->get();

        return response()->json($modules);
    }
}
