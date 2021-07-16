<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Module;
use App\Models\Tag;
use App\Models\Unit;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->search_query;
        $courseIds = auth()
            ->user()
            ->dealer
            ->courses
            ->pluck('id')
            ->toArray();

        $modulesQ = Module::search($query)
            ->get()
            ->filter(function ($item) use ($courseIds) {
                if (empty($item->category) || ! is_null($item->deleted_at)) {
                    return false;
                }

                return in_array($item->category->course_id, $courseIds);
            });

        $unitsQ = Unit::search($query)
            ->get()
            ->filter(function ($item) use ($courseIds) {
                if (
                    ! is_null($item->deleted_at) ||
                    empty($item->module) ||
                    empty($item->module->category) ||
                    ! in_array($item->module->category->course_id, $courseIds)
                ) {
                    return false;
                }

                return true;
            })
            ->unique('name');

        $unitsCollection = $unitsQ;
        $hashtagsUnitResults = collect([]);

        $tags = Tag::search($query)->get();
        $tags->each(function ($tag) use ($hashtagsUnitResults, $unitsCollection) {
            if (is_null($tag->units)) {
                return true;
            }

            $tag->units->each(function ($unit) use ($hashtagsUnitResults, $unitsCollection) {
                if ($unitsCollection->contains($unit)) {
                    $hashtagsUnitResults->push($unit);
                }
            });
        });
        $hashtagsUnitResults = $hashtagsUnitResults->unique('id');

        return response()->json([
            'status' => 'success',
            'results' => [
                'units' => $unitsQ->values(),
                'modules' => $modulesQ->values(),
                'hashtag units' => $hashtagsUnitResults->values(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
