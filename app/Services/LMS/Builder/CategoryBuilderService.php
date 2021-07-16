<?php

namespace App\Services\LMS\Builder;

use App\Models\Category;
use App\Models\CategoryBuild;
use App\Models\CategoryModuleBuild;
use App\Models\ModuleBuild;

class CategoryBuilderService
{
    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $search = $request->search;
        $status = $request->type;
        $categoryBuilds = CategoryBuild::with('category', 'modules', 'modules.module_build');

        if ($status == 'inactive') {
            $categoryBuilds->onlyTrashed();
        } elseif ($status == 'all') {
            $categoryBuilds->withTrashed();
        }

        if ($search) {
            $categoryBuilds->where('name', 'LIKE', '%'.$search.'%');
        }

        if (! isset($request->sortBy)) {
            $categoryBuilds->orderByDesc('created_at');
        }

        return $categoryBuilds->paginate($perPage);
    }

    public function store($request)
    {
        $category_build = new CategoryBuild;
        $category_build->fill($request->all());
        $category_build->save();

        $this->updateModuleBuilds($request->modules, $category_build);

        return response($category_build);
    }

    public function update($request)
    {
        $category_build = CategoryBuild::findOrFail($request->id);
        $category_build->fill($request->all());
        $category_build->save();

        $this->updateModuleBuilds($request->modules, $category_build);

        return response($category_build);
    }

    private function updateModuleBuilds($module_ids, $category_build)
    {
        $category_build->modules()->forceDelete();
        foreach ($module_ids as $i => $id) {
            $category_build->modules()->create([
                'module_build_id' =>  $id,
                'category_build_od' =>  $category_build->id,
            ]);
        }
    }

    public function destroy($id)
    {
        if (CategoryBuild::destroy($id)) {
            return response()->json([
                'success'   =>  true,
            ]);
        }
        abort(404);
    }

    public function restore($id)
    {
        $category_build = CategoryBuild::onlyTrashed()->get()->find($id);
        $category_build->restore();

        return response()->json([
            $category_build,
        ]);
    }

    public function fetchCategoriesByCourse($request)
    {
        $course_id = $request->course_id;
        $categories = Category::with('build')
            ->where('course_id', $course_id)
            ->get();

        return response()->json($categories);
    }

    public function fetchModuleBuildsByCategory($request)
    {
        $category_id = $request->category_id;

        $modules = ModuleBuild::with('category_build')
            ->whereHas('module.category', function ($query) use ($category_id) {
                $query->where('id', $category_id);
            })
            ->get();

        return response()->json($modules);
    }
}
