<?php

namespace App\Http\Controllers\LMS\Builder;

use App\Http\Controllers\Controller;
use App\Services\LMS\Builder\QuizBuilderService;
use Illuminate\Http\Request;

class QuizBuilderRestoreController extends Controller
{
    public $quizBuilderService;

    public function __construct(QuizBuilderService $quizBuilderService)
    {
        $this->quizBuilderService = $quizBuilderService;
    }

    public function update($id)
    {
        return $this->quizBuilderService->restore($id);
    }
}
