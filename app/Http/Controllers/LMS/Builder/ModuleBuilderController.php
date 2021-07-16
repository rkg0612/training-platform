<?php

namespace App\Http\Controllers\LMS\Builder;

use App\Http\Controllers\Controller;
use App\Http\Requests\LMS\Builder\Modules\Store;
use App\Http\Requests\LMS\Builder\Modules\Update;
use Facades\App\Services\LMS\Builder\ModuleBuilderService;
use Illuminate\Http\Request;

class ModuleBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ModuleBuilderService::index($request);
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
        return ModuleBuilderService::store($request);
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
    public function update(Update $request)
    {
        return ModuleBuilderService::update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ModuleBuilderService::destroy($id);
    }

    public function restore($id)
    {
        return ModuleBuilderService::restore($id);
    }

    public function fetchModulesByCourse(Request $request)
    {
        return ModuleBuilderService::fetchModulesByCourse($request);
    }

    public function fetchUnitsByModules(Request $request)
    {
        return ModuleBuilderService::fetchUnitsByModules($request);
    }

    public function fetchCourses(Request $request)
    {
        return ModuleBuilderService::fetchCourses($request);
    }
}
