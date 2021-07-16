<?php

namespace App\Services\LMS\Builder;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionAnswer;
use Symfony\Component\Console\Question\Question;

class QuizDuplicateService
{
    public function duplicate($id)
    {
        $quiz = Quiz::find($id);
        $quiz->load('questions.answers');
        $duplicate = $quiz->replicate();
        $duplicate->save();

        $quizId = $duplicate->id;

        $quiz->questions->each(function ($question) use ($quizId) {
            $newQuestionRecord = new QuizQuestion;
            $newQuestionRecord->quiz_id = $quizId;
            $newQuestionRecord->value = $question->value;
            $newQuestionRecord->save();

            $questionId = $newQuestionRecord->id;

            $question->answers->each(function ($answer) use ($questionId) {
                $questionAnswer = new QuizQuestionAnswer;
                $questionAnswer->value = $answer->value;
                $questionAnswer->is_correct = $answer->is_correct;
                $questionAnswer->quiz_question_id = $questionId;
                $questionAnswer->save();
            });
        });

        return Quiz::with(['questions.answers'])->find($duplicate->id);
    }
}
