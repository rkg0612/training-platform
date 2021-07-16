<?php

namespace App\Http\Controllers\LMS\Builder;

use App\Http\Controllers\Controller;
use App\Services\LMS\Builder\QuizDuplicateService;
use Illuminate\Http\Request;

class QuizDuplicateController extends Controller
{
    public $quizDuplicateService;

    public function __construct(QuizDuplicateService $quizDuplicateService)
    {
        $this->quizDuplicateService = $quizDuplicateService;
    }

    public function store(Request $request)
    {
        return $this->quizDuplicateService->duplicate($request->id);
    }
}
