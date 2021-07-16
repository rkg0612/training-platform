<?php

namespace App\Http\Controllers;

use App\Services\PlaylistUnitService;
use Illuminate\Http\Request;

class PlaylistUnitController extends Controller
{
    /**
     * @var PlaylistUnitService
     */
    private $playlistUnitService;

    public function __construct(PlaylistUnitService $playlistUnitService)
    {
        $this->playlistUnitService = $playlistUnitService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response($this->playlistUnitService->index($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($this->playlistUnitService->store($request));
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
        //
    }
}
