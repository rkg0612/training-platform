<?php

namespace App\Http\Controllers;

use App\Services\PlaylistAssignService;
use Illuminate\Http\Request;

class PlaylistAssignController extends Controller
{
    /**
     * @var PlaylistAssignService
     */
    private $playlistAssignService;

    public function __construct(PlaylistAssignService $playlistAssignService)
    {
        $this->playlistAssignService = $playlistAssignService;
    }

    public function index(Request $request)
    {
        return response($this->playlistAssignService->index($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->playlistAssignService->store($request);
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
