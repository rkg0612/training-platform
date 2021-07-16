<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeaturedVideo\Store;
use App\Http\Requests\FeaturedVideo\Update;
use App\Services\VideoOfTheDay\FeaturedVideoService;
use Illuminate\Http\Request;

class FeaturedVideoController extends Controller
{
    private $featuredVideoService;

    public function __construct(FeaturedVideoService $featuredVideoService)
    {
        $this->featuredVideoService = $featuredVideoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->featuredVideoService->show($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        return $this->featuredVideoService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->featuredVideoService->searchById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        return $this->featuredVideoService->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->featuredVideoService->destroy($id);
    }
}
