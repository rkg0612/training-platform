<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\LMS\Course\Store;
use App\Http\Requests\LMS\Course\Update;
use App\Http\Resources\CourseCollection;
use Facades\App\Services\LMS\CourseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CourseCollection
     */
    public function index(Request $request)
    {
        return CourseService::index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @return Response
     */
    public function store(Store $request)
    {
        return CourseService::store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @return Response
     */
    public function update(Update $request)
    {
        return CourseService::update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return CourseService::delete($id);
    }
}
