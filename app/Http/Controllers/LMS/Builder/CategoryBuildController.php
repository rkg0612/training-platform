<?php

namespace App\Http\Controllers\LMS\Builder;

use App\CategoryBuild;
use App\Http\Controllers\Controller;
use App\Http\Requests\LMS\Builder\Category\Store;
use App\Http\Requests\LMS\Builder\Category\Update;
use Facades\App\Services\LMS\Builder\CategoryBuilderService;
use Illuminate\Http\Request;

class CategoryBuildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CategoryBuilderService::index($request);
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
        return CategoryBuilderService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryBuild $categoryBuild)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryBuild $categoryBuild)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CategoryBuild  $categoryBuild
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        return CategoryBuilderService::update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return CategoryBuilderService::destroy($id);
    }

    public function restore($id)
    {
        return CategoryBuilderService::restore($id);
    }

    public function fetchCategoriesByCourse(Request $request)
    {
        return CategoryBuilderService::fetchCategoriesByCourse($request);
    }

    public function fetchModuleBuildsByCategory(Request $request)
    {
        return CategoryBuilderService::fetchModuleBuildsByCategory($request);
    }
}
