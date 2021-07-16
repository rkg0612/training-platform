<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\LMS\Category\Store;
use App\Http\Requests\LMS\Category\Update;
use Facades\App\Services\LMS\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CategoryService::index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        return CategoryService::store($request);
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
        return CategoryService::update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return CategoryService::destroy($id);
    }

    public function restore($id)
    {
        return CategoryService::restore($id);
    }

    public function fetchCategories($request = null)
    {
        return CategoryService::fetchCategoriesForSelection($request);
    }
}
