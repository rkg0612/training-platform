<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\LMS\Module\Store;
use App\Http\Requests\LMS\Module\Update;
use Facades\App\Services\LMS\CategoryService;
use Facades\App\Services\LMS\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ModuleService::index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        return ModuleService::store($request);
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
        return ModuleService::update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ModuleService::delete($id);
    }

    public function restore($id)
    {
        return ModuleService::restore($id);
    }

    public function fetchModules($request = null)
    {
        return ModuleService::fetchModulesForSelection($request);
    }
}
