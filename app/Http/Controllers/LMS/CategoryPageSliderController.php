<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\CategoryPageSliderService;
use Illuminate\Http\Request;

class CategoryPageSliderController extends Controller
{
    public $categoryPageSliderService;

    public function __construct(CategoryPageSliderService $categoryPageSliderService)
    {
        $this->categoryPageSliderService = $categoryPageSliderService;
    }

    public function index()
    {
        return $this->categoryPageSliderService->index();
    }

    public function show($id)
    {
        return $this->categoryPageSliderService->show($id);
    }
}
