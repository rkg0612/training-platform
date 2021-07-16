<?php

namespace App\Http\Controllers\LMS\Builder;

use App\Http\Controllers\Controller;
use App\Http\Requests\LMS\Builder\Course\Store;
use App\Http\Requests\LMS\Builder\Course\Update;
use App\Models\CourseBuild;
use Facades\App\Services\LMS\Builder\CourseBuilderService;
use Illuminate\Http\Request;

class CourseBuildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CourseBuilderService::index($request);
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
    public function store(Store $request)
    {
        return CourseBuilderService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CourseBuild  $courseBuild
     * @return \Illuminate\Http\Response
     */
    public function show(CourseBuild $courseBuild)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CourseBuild  $courseBuild
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseBuild $courseBuild)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseBuild  $courseBuild
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        return CourseBuilderService::update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseBuild  $courseBuild
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return CourseBuilderService::destroy($request);
    }

    public function restore($id)
    {
        return CourseBuilderService::restore($request);
    }

    public function fetchCategoryBuildsByCourse(Request $request)
    {
        return CourseBuilderService::fetchCategoryBuildsByCourse($request);
    }
}
