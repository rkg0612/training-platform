<?php

namespace App\Services\LMS;

use App\Models\Quiz;
use App\Models\Unit;
use App\Models\UserAnsweredQuiz;
use Illuminate\Support\Collection;

class QuizService
{
    public function store(Collection $answers, $unitId)
    {
        $unit = Unit::find($unitId);

        $quiz = Quiz::with(['questions.answers'])->find($unit->quiz_id);

        $quiz->questions = $unit->quiz->questions->map(function ($question) use ($answers) {
            $question['user_answer'] = $answers->where('questionId', $question->id)->pluck('answerId')->toArray();

            return $question;
        });

        $totalScore = $this->computeTotalScore($quiz->questions->load('answers'));

        $form = $quiz->questions->toJson();

        $data = [
            'user_id' => auth()->user()->id,
            'unit_id' => $unitId,
            'quiz_id' => $quiz->id,
            'total_number_of_questions' => $quiz->questions->count(),
            'total_score' => $totalScore,
            'quiz_form' => $form,
        ];

        $this->createUserAnsweredQuizRecord($data);

        return response()->json([
            'success' => true,
        ]);
    }

    public function checkIfUserAlreadyAnsweredTheQuiz($unitId, $quizId)
    {
        return UserAnsweredQuiz::where('unit_id', $unitId)
            ->where('quiz_id', $quizId)
            ->exists();
    }

    public function show($quizId, $unitId)
    {
        $result = UserAnsweredQuiz::where('unit_id', $unitId)
            ->where('quiz_id', $quizId)
            ->where('user_id', auth()->user()->id);

        return response()->json([
            'success' => $result->exists(),
            'data' => $result->first(),
        ]);
    }

    public function createUserAnsweredQuizRecord(array $data)
    {
        return UserAnsweredQuiz::create($data);
    }

    public function computeTotalScore(Collection $questions)
    {
        return $questions->map(function ($question) {
            $isMultipleAnswer = $question->answers->where('is_correct', 1)->count() >= 2;
            if ($isMultipleAnswer) {
                $correctAnswersId = $question->answers->where('is_correct', 1)->pluck('id')->toArray();

                return (count(array_diff($correctAnswersId, $question->user_answer)) === 0) ? 1 : 0;
            }

            return $question->answers
                ->whereIn('id', $question->user_answer)
                ->where('is_correct', 1)
                ->count() >= 1 ? 1 : 0;
        })->sum();
    }
}
