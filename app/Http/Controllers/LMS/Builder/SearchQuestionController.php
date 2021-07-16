<?php

namespace App\Http\Controllers\LMS\Builder;

use App\Http\Controllers\Controller;
use App\Services\LMS\Builder\SearchQuestionService;
use Illuminate\Http\Request;

class SearchQuestionController extends Controller
{
    public $searchQuestionService;

    public function __construct(SearchQuestionService $searchQuestionService)
    {
        $this->searchQuestionService = $searchQuestionService;
    }

    public function index(Request $request)
    {
        return $this->searchQuestionService->search($request);
    }
}
