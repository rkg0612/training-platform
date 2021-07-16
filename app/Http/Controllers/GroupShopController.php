<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupShop\Store;
use App\Http\Requests\GroupShop\Update;
use App\Services\InternetShop\GroupShopService;
use Illuminate\Http\Request;

class GroupShopController extends Controller
{
    private $groupShopService;

    public function __construct(GroupShopService $groupShopService)
    {
        $this->groupShopService = $groupShopService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->groupShopService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        return $this->groupShopService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->groupShopService->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        return $this->groupShopService->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->groupShopService->destroy($id);
    }
}
