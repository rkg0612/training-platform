<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternetPost\Store;
use App\Http\Requests\InternetPost\Update;
use App\Models\InternetShop;
use App\Services\InternetShop\InternetPostService;

class InternetPostController extends Controller
{
    private $internetPostService;

    public function __construct(InternetPostService $internetPostService)
    {
        $this->internetPostService = $internetPostService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        return $this->internetPostService->store($request);
    }

    public function show($id)
    {
        return InternetShop::with(['truecar_fields', 'chatScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }, 'emailScreenshots' => function ($screenshot) {
            return $screenshot->orderBy('order_by', 'asc');
        }])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InternetShop  $internetShop
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, InternetShop $internetShop)
    {
        return $this->internetPostService->update($request, $internetShop);
    }
}
