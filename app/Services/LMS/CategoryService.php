<?php

namespace App\Services\LMS;

use App\Helpers\WithFileUpload;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    use WithFileUpload;

    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $status = $request->type;
        $search = $request->search;

        $category = Category::latest();

        if (! empty($search)) {
            $category->where('name', 'like', '%'.$search.'%');
        }

        if ($status == 'inactive') {
            $category->onlyTrashed();
        } elseif ($status == 'all') {
            $category->withTrashed();
        }

        return $category->paginate($perPage);
    }

    public function store($request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->course_id = $request->course;
        $category->save();

        $fileName = $this->saveImageAs($request->thumbnail, "categories/$category->id/", 'jpg', 's3');

        $category->update([
            'thumbnail' => Storage::disk('s3')->url("categories/$category->id/$fileName"),
        ]);

        return response()->json([
            $category,
        ]);
    }

    public function update($request)
    {
        $category = Category::find($request['id']);
        $category->name = $request->name;
        $category->course_id = $request->course;
        $category->save();

        if ($request->thumbnail && $request->thumbnail !== 'undefined') {
            $oldFile = Str::after($category->thumbnail, 'com/');

            if (Storage::disk('s3')->exists($oldFile)) {
                Storage::disk('s3')->delete($oldFile);
            }

            $fileName = $this->saveImageAs($request->thumbnail, "categories/$category->id/", 'jpg', 's3');

            $category->update([
                'thumbnail' => Storage::disk('s3')->url("categories/$category->id/$fileName"),
            ]);
        }

        return response()->json([
            $category,
        ]);
    }

    public function show($id)
    {
        return Category::with(['courses.course', 'excludedUsers.user'])->find($id);
    }

    public function destroy($id)
    {
        if (Category::destroy($id)) {
            return response('success');
        }
        abort(404);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->get()->find($id);
        $category->restore();

        return response()->json(
            $category
        );
    }

    public function fetchCategoriesForSelection()
    {
        $categories = Category::without('course')->select('id', 'name')->get();

        return response()->json($categories);
    }
}
