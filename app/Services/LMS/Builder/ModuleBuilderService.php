<?php

namespace App\Services\LMS\Builder;

use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleBuild;
use App\Models\ModuleSeries;
use App\Models\SeriesUnit;
use App\Models\Unit;

class ModuleBuilderService
{
    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $status = $request->type;
        $search = $request->search;
        $moduleBuilds = ModuleBuild::with(['module', 'series', 'series.units', 'module.category.course']);

        if ($status == 'inactive') {
            $moduleBuilds->onlyTrashed();
        } elseif ($status == 'all') {
            $moduleBuilds->withTrashed();
        }

        if ($search) {
            $moduleBuilds->where('name', 'LIKE', '%'.$search.'%');
        }

        if (! isset($request->sortBy)) {
            $moduleBuilds->orderByDesc('created_at');
        }

        return $moduleBuilds->paginate($perPage);
    }

    public function store($request)
    {
        $module_build = new ModuleBuild;
        $module_build->fill($request->all());
        $module_build->save();

        $this->updateSeriesFromSections($request->unitSection, $module_build);

        $id = $module_build->id;
        $responseModuleBuilds = ModuleBuild::with(['module', 'module.category.course'])->findOrFail($id);

        return response($responseModuleBuilds);
    }

    public function update($request)
    {
        $module_build = ModuleBuild::findOrFail($request->id);
        $module_build->fill($request->all());
        $module_build->save();

        $this->updateSeriesFromSections($request->unitSection, $module_build);

        $id = $module_build->id;
        $responseModuleBuilds = ModuleBuild::with(['module', 'module.category.course'])->findOrFail($id);

        return response($responseModuleBuilds);

        return response($module_build);
    }

    private function updateSeriesFromSections($sections, $module_build)
    {
        foreach ($module_build->series as $series) {
            $series->units()->forceDelete();
            $series->forceDelete();
        }

        foreach ($sections as $section) {
            $module_series = new ModuleSeries;
            $module_series->name = $section['name'];
            $module_series->is_banner = $section['is_banner'] == 1 ? true : false;
            $module_series->module_build_id = $module_build->id;
            $module_series->save();

            foreach ($section['units'] as $unit) {
                $series_unit = new SeriesUnit;
                $series_unit->module_series_id = $module_series->id;
                $series_unit->unit_id = $unit;
                $series_unit->save();
            }
        }
    }

    public function destroy($id)
    {
        if (ModuleBuild::destroy($id)) {
            return response()->json([
                'success'   =>  true,
            ]);
        }
        abort(404);
    }

    public function restore($id)
    {
        $module_build = ModuleBuild::onlyTrashed()->get()->find($id);
        $module_build->restore();

        return response()->json([
            $module_build,
        ]);
    }

    public function fetchModulesByCourse($request)
    {
        $course_id = $request->course_id;
        $modules = Module::doesntHave('build')
            ->whereHas('category', function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            })
            ->setEagerLoads([])
            ->select('id', 'name')
            ->get();

        return response()->json($modules);
    }

    public function fetchUnitsByModules($request)
    {
        $course_id = $request->course_id;
        $module_id = $request->module_id;
        $units = Unit::doesntHave('series')
            ->where('module_id', $module_id)
            ->whereHas('module.category', function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            })
            ->select('id', 'name')
            ->get();

        return response()->json($units);
    }

    public function fetchCourses()
    {
        $courses = Course::without('dealers')->select('id', 'name')->get();

        return $courses;
    }
}
