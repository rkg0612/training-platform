<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternetShop\Store;
use App\Http\Requests\InternetShop\Update;
use App\Services\InternetShop\InternetShopService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternetShopController extends Controller
{
    private $internetShopService;

    public function __construct(InternetShopService $internetShopService)
    {
        $this->internetShopService = $internetShopService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->internetShopService->index($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @return bool
     */
    public function store(Store $request)
    {
        return $this->internetShopService->store($request->validated(), $request->allFiles());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->internetShopService->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return bool
     */
    public function update(Update $request)
    {
        return $this->internetShopService->update($request->validated(), $request->allFiles(), $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        return $this->internetShopService->destroy($id);
    }

    public function restore($id)
    {
        return $this->internetShopService->restore($id);
    }

    public function fetchSms($id)
    {
        return $this->internetShopService->fetchSms($id);
    }
}
