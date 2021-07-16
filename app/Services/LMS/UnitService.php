<?php

namespace App\Services\LMS;

use App\Helpers\WithFileUpload;
use App\Models\Tag;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class UnitService
{
    use WithFileUpload;

    public function index($request)
    {
        $perPage = isset($request->per_page) ? $request->per_page : 5;
        $status = $request->type;
        $search = $request->search;
        $sort_by = $request->sortBy;
        $desc = $request->sortDesc ? 'desc' : 'asc';

        $unit = Unit::with(['tags', 'quiz'])
                    ->latest();

        if (! empty($search)) {
            $unit->where('name', 'like', '%'.$search.'%');
        }

        if ($status == 'inactive') {
            $unit->onlyTrashed();
        } elseif ($status == 'all') {
            $unit->withTrashed();
        }

        return $unit->paginate($perPage);
    }

    public function store($request)
    {
        $unit = new Unit;
        $unit->name = $request->name;
        $unit->module_id = $request->module_id;
        $unit->description = $request->description;
        $unit->video_duration = $request->video_duration;
        $unit->call_guide_link = $request->call_guide_link;
        $unit->content = Purify::clean($request->content);
        if ($request->quiz_id) {
            $unit->quiz_id = $request->quiz_id;
        }
        $unit->save();

        $fileName = $this->saveImageAs($request->thumbnail, "units/$unit->id/", 'png', 's3');

        $unit->update([
            'thumbnail' => Storage::disk('s3')->url("units/$unit->id/$fileName"),
        ]);

        $tags = $request->tags;

        foreach ($request->tags as $tag) {
            if (! is_numeric($tag)) {
                $tags[] = Tag::create(['name' => $tag])->id;
            }
        }

        $unit->tags()->sync(array_filter($tags, 'is_numeric'));

        return response()->json([
            $unit->load('module', 'quiz'),
        ]);
    }

    public function update($request)
    {
        $unit = Unit::findOrFail($request->id);
        $unit->name = $request->name;
        $unit->module_id = $request->module_id;
        $unit->description = $request->description;
        $unit->video_duration = $request->video_duration;
        $unit->call_guide_link = $request->call_guide_link;
        $unit->content = Purify::clean($request->content);
        if ($request->quiz_id) {
            $unit->quiz_id = $request->quiz_id;
        }
        $unit->save();

        if ($request->thumbnail && $request->thumbnail !== 'undefined') {
            $oldFile = Str::after($unit->thumbnail, 'com/');

            if (Storage::disk('s3')->exists($oldFile)) {
                Storage::disk('s3')->delete($oldFile);
            }

            $fileName = $this->saveImageAs($request->thumbnail, "units/$unit->id/", 'png', 's3');

            $unit->update([
                'thumbnail' => Storage::disk('s3')->url("units/$unit->id/$fileName"),
            ]);
        }

        $tags = $request->tags;

        foreach ($request->tags as $i => $tag) {
            if (! is_numeric($tag)) {
                $tags[] = Tag::create(['name' => $tag])->id;
                unset($tags[$i]);
            }
        }

        $unit->tags()->sync($tags);

        return response()->json([
            $unit->load('module', 'quiz'),
        ]);
    }

    public function delete($id)
    {
        if (Unit::find($id)->delete()) {
            return response('success');
        }

        return abort(404);
    }

    public function restore($id)
    {
        $unit = Unit::onlyTrashed()->get()->find($id);
        $unit->restore();

        return response()->json(
            $unit->load('module')
        );
    }
}
