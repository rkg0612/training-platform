<?php

namespace App\Http\Controllers;

use App\Services\PlaylistAddUnitService;
use Illuminate\Http\Request;

class PlaylistAddUnitController extends Controller
{
    /**
     * @var PlaylistAddUnitService
     */
    private $playlistAddUnitService;

    public function __construct(PlaylistAddUnitService $playlistAddUnitService)
    {
        $this->playlistAddUnitService = $playlistAddUnitService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($this->playlistAddUnitService->store($request));
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
