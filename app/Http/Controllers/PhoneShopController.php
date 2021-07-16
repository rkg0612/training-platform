<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneShop\Store;
use App\Http\Requests\PhoneShop\Update;
use App\Services\PhoneShop\PhoneShopService;
use Illuminate\Http\Request;

class PhoneShopController extends Controller
{
    private $phoneShopService;

    public function __construct(PhoneShopService $phoneShopService)
    {
        $this->phoneShopService = $phoneShopService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->phoneShopService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        return $this->phoneShopService->store($request->validated(), $request->allFiles());
    }

    public function show($id)
    {
        return $this->phoneShopService->show($id);
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
        return $this->phoneShopService->update($request->validated(), $request->allFiles());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->phoneShopService->destroy($id);
    }
}
