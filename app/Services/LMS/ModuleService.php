<?php

namespace App\Services\LMS;

use App\Helpers\WithFileUpload;
use App\Models\Module;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class ModuleService
{
    use WithFileUpload;

    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $status = $request->type;
        $search = $request->search;

        $module = Module::latest();

        if (! empty($search)) {
            $module->where('name', 'like', '%'.$search.'%');
        }

        if ($status == 'inactive') {
            $module->onlyTrashed();
        } elseif ($status == 'all') {
            $module->withTrashed();
        }

        return $module->paginate($perPage);
    }

    public function store($request)
    {
        $module = new Module;
        $module->name = $request->name;
        $module->description = Purify::clean($request->description);
        $module->call_guide_link = $request->call_guide_link;
        $module->category_id = $request->category_id;
        $module->save();

        if ($request->thumbnail) {
            $fileName = $this->saveImageAs($request->thumbnail, "modules/$module->id/", 'jpg', 's3');

            $module->update([
                'thumbnail' => Storage::disk('s3')->url("modules/$module->id/$fileName"),
            ]);
        }

        if ($request->link) {
            $module->update([
                'thumbnail' => $request->link,
            ]);
        }

        return response()->json([
            $module,
        ]);
    }

    public function update($request)
    {
        $module = Module::findOrFail($request->id);
        $module->name = $request->name;
        $module->description = Purify::clean($request->description);
        $module->call_guide_link = $request->call_guide_link;
        $module->category_id = $request->category_id;
        $module->save();

        if ($request->thumbnail && $request->thumbnail !== 'undefined') {
            if (Str::contains($request->thumbnail, ['aws', 's3'])) {
                $oldFile = Str::after($module->thumbnail, 'com/');

                if (Storage::disk('s3')->exists($oldFile)) {
                    Storage::disk('s3')->delete($oldFile);
                }
            }

            $fileName = $this->saveImageAs($request->thumbnail, "modules/$module->id/", 'jpg', 's3');

            $module->update([
                'thumbnail' => Storage::disk('s3')->url("modules/$module->id/$fileName"),
            ]);
        }

        if ($request->link) {
            $module->update([
                'thumbnail' => $request->link,
            ]);
        }

        return response()->json([
            $module,
        ]);
    }

    public function delete($id)
    {
        if (Module::find($id)->delete()) {
            return response('success');
        }

        return abort(404);
    }

    public function restore($id)
    {
        $module = Module::onlyTrashed()->get()->find($id);
        $module->restore();

        return response()->json(
            $module
        );
    }

    public function fetchModulesForSelection()
    {
        $modules = Module::without('category')->select('id', 'name')->get();

        return response()->json($modules);
    }
}
