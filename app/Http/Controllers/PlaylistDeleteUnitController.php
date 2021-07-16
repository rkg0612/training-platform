<?php

namespace App\Http\Controllers;

use App\Services\PlaylistDeleteUnitService;
use Illuminate\Http\Request;

class PlaylistDeleteUnitController extends Controller
{
    /**
     * @var PlaylistDeleteUnitService
     */
    private $playlistDeleteUnitService;

    public function __construct(PlaylistDeleteUnitService $playlistDeleteUnitService)
    {
        $this->playlistDeleteUnitService = $playlistDeleteUnitService;
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
        return $this->playlistDeleteUnitService->store($request);
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
