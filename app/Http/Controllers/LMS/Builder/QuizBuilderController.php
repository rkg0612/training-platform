<?php

namespace App\Http\Controllers\LMS\Builder;

use App\Http\Controllers\Controller;
use App\Services\LMS\Builder\QuizBuilderService;
use Illuminate\Http\Request;

class QuizBuilderController extends Controller
{
    public $quizBuilderService;

    public function __construct(QuizBuilderService $quizBuilderService)
    {
        $this->quizBuilderService = $quizBuilderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->quizBuilderService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->quizBuilderService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->quizBuilderService->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->quizBuilderService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->quizBuilderService->destroy($id);
    }
}
