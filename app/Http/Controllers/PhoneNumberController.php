<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneNumber\Store;
use App\Http\Requests\PhoneNumber\Update;
use App\Services\PhoneNumberService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhoneNumberController extends Controller
{
    private $phoneNumberService;

    public function __construct(PhoneNumberService $phoneNumberService)
    {
        $this->phoneNumberService = $phoneNumberService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->phoneNumberService->index($request);
    }

    public function fetchNumbersToManage(Request $request)
    {
        return $this->phoneNumberService->fetchNumbersToManage($request);
    }

    public function searchNumbers(Request $request)
    {
        return $this->phoneNumberService->searchNumbers($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @return Response
     */
    public function store(Store $request)
    {
        return $this->phoneNumberService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Update $request)
    {
        return $this->phoneNumberService->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return int
     */
    public function destroy(Request $request)
    {
        return $this->phoneNumberService->destroy($request);
    }
}
