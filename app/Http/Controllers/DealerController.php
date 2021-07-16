<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewDealer;
use App\Models\Dealer;
use App\Services\DealerService;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    private $dealerService;

    public function __construct(DealerService $dealerService)
    {
        $this->dealerService = $dealerService;
    }

    public function index(Request $request)
    {
        return $this->dealerService->index($request);
    }

    public function store(NewDealer $request)
    {
        return $this->dealerService->store($request);
    }

    public function update(Request $request, Dealer $dealer)
    {
        return $this->dealerService->update($request, $dealer);
    }

    public function destroy(Dealer $dealer)
    {
        return $this->dealerService->destroy($dealer);
    }
}
