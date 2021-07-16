<?php

namespace App\Http\Controllers;

use App\Services\SecretShopTableService;
use Illuminate\Http\Request;

class SecretShopTableController extends Controller
{
    public $secretShopTableService;

    public function __construct(SecretShopTableService $secretShopTableService)
    {
        $this->secretShopTableService = $secretShopTableService;
    }

    public function index(Request $request)
    {
        return $this->secretShopTableService->index($request->all());
    }
}
