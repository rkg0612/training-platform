<?php

namespace App\Http\Controllers;

use App\Models\CourseBuild;
use App\Models\SearchAnalytic;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $records = SearchAnalytic::groupBy('model_id')->selectRaw('count(*) as total', ['model_id'])->get();
        $records = DB::table('search_analytics')
                 ->select('model_id', DB::raw('count(*) as total'))
                 ->where('dealer_id', auth()->user()->dealer_id)
                 ->groupBy('model_id')
                 ->get();

        if ($records->isEmpty()) {
            $units = $this->getFirstFewUnits();
            if ($units->isNotEmpty()) {
                return response($units->take(10));
            }
        }

        $unitIds = $records->take(10)->pluck('model_id');
        $units = Unit::whereIn('id', $unitIds)->get();
        // response looks like: [{"model_id":129,"total":1},{"model_id":130,"total":1}]
        return response($units);
    }

    public function getFirstFewUnits()
    {
        $build = CourseBuild::whereIn('course_id', auth()->user()->dealer->courses->pluck('id'))
            ->first();
        $units = collect([]);
        if (empty($build)) {
            return $units;
        }

        foreach ($build->category_builds as $categoryBuild) {
            $category = [];
            $category['name'] = $categoryBuild->category_build->category->name;
            $results = $categoryBuild->category_build->modules->map(function ($item) {
                $modUnits = $item->module_build->series->map(function ($item) {
                    return $item->units->pluck('unit');
                })->flatten();

                return $modUnits;
            });
            $units = $units->merge($results);
            if ($units->flatten()->count() > 10) {
                break;
            }
        }

        return $units->flatten();
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
        $currentUser = auth()->user();
        $currentDealer = $currentUser->dealer;
        $analytic = new SearchAnalytic;
        $analytic->model_id = $request->model_id;
        $analytic->model_type = $request->model_type;
        $analytic->dealer_id = $currentDealer->id;
        $analytic->user_id = $currentUser->id;
        $analytic->save();
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
