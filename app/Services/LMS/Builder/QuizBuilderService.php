<?php

namespace App\Services\LMS\Builder;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionAnswer;

class QuizBuilderService
{
    public function index($request)
    {
        if (empty($request->status)) {
            abort('400');
        }

        $status = explode(',', $request->status);

        $quizzes = Quiz::query()
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'LIKE', '%'.$search.'%');
            })
            ->when($status, function ($query, $status) {
                if (count($status) >= 2) {
                    $query->withTrashed();
                } elseif (in_array('Active', $status)) {
                    $query->withoutTrashed();
                } elseif (in_array('Inactive', $status)) {
                    $query->onlyTrashed();
                }
            })
            ->with([
                'questions.answers',
            ]);

        return $quizzes
            ->latest()
            ->paginate($request->itemsPerPage ?? 5);
    }

    public function show($id)
    {
        return Quiz::with([
            'questions.answers',
        ])->withTrashed()->find($id);
    }

    public function store($request)
    {
        $quiz = Quiz::create([
            'title' => $request->title,
        ]);

        collect($request->questions)->each(function ($question) use ($quiz) {
            $questionRecord = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'value' => $question['value'],
            ]);

            $this->saveToQuizQuestionAnswer($question['answers'], $questionRecord->id);
        });

        return response()->json([
            'success' => true,
        ]);
    }

    public function saveToQuizQuestionAnswer($answers, $questionId)
    {
        collect($answers)->each(function ($answer) use ($questionId) {
            QuizQuestionAnswer::create([
                'quiz_question_id' => $questionId,
                'value' => $answer['value'],
                'is_correct' => isset($answer['is_correct']) ? true : false,
            ]);
        });
    }

    public function update($id, $request)
    {
        $quiz = Quiz::find($id);
        $quiz->title = $request['title'];
        $quiz->save();

        $existingQuestionsIdFromRequest = (collect($request['questions']))->map(function ($question) {
            if (isset($question['id'])) {
                return $question['id'];
            }

            return null;
        })->toArray();

        $existingQuestionsIdFromDatabase = QuizQuestion::where('quiz_id', $id)->pluck('id')->toArray();

        $removedQuestionsId = array_diff($existingQuestionsIdFromDatabase, $existingQuestionsIdFromRequest);

        QuizQuestion::destroy($removedQuestionsId);

        collect($request['questions'])->each(function ($question) use ($id) {
            $questionRecord = QuizQuestion::updateOrCreate(['id' => isset($question['id']) ? $question['id'] : ''],
                [
                    'quiz_id' => $id,
                    'value' => $question['value'],
                ]
            );

            if (isset($question['id'])) {
                $oldAnswers = QuizQuestionAnswer::query()
                    ->where('quiz_question_id', $question['id'])
                    ->pluck('id')
                    ->toArray();
                $currentAnswers = collect($question['answers'])->pluck('id')->toArray();
                $removedAnswers = array_diff($oldAnswers, $currentAnswers);
                QuizQuestionAnswer::destroy($removedAnswers);
            }

            collect($question['answers'])->filter()->each(function ($answer) use ($questionRecord) {
                QuizQuestionAnswer::updateOrCreate([
                    'id' => isset($answer['id']) ? $answer['id'] : '',
                    'quiz_question_id' => $questionRecord->id,
                ], [
                    'quiz_question_id' => $questionRecord->id,
                    'value' => $answer['value'],
                    'is_correct' => $this->isAnswerCorrect($answer),
                ]);
            });
        });

        return response()->json([
            'success' => true,
            'data' => Quiz::with('questions.answers')->find($id),
        ]);
    }

    public function isAnswerCorrect($answer)
    {
        if (isset($answer['isCorrect']) && $answer['isCorrect'] === true
            || isset($answer['is_correct']) && $answer['is_correct'] == true) {
            return true;
        }

        return false;
    }

    public function destroy($id)
    {
        if (Quiz::find($id)->delete()) {
            return response()->json([
                'success' => true,
            ]);
        }

        abort(500, 'Can\'t archived quiz');
    }

    public function restore($id)
    {
        Quiz::withTrashed()
            ->find($id)
            ->restore();

        return response()->json([
            'success' => true,
        ]);
    }
}
