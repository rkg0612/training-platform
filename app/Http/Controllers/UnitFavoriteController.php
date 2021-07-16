<?php

namespace App\Http\Controllers;

use App\Services\UnitFavoriteService;
use Illuminate\Http\Request;

class UnitFavoriteController extends Controller
{
    /**
     * @var UnitFavoriteService
     */
    private $unitFavoriteService;

    public function __construct(UnitFavoriteService $unitFavoriteService)
    {
        $this->unitFavoriteService = $unitFavoriteService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->unitFavoriteService->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($this->unitFavoriteService->store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response($this->unitFavoriteService->destroy($id));
    }
}
