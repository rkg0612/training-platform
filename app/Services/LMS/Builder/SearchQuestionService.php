<?php

namespace App\Services\LMS\Builder;

use App\Models\QuizQuestion;

class SearchQuestionService
{
    public function search($request)
    {
        return QuizQuestion::query()
            ->with('answers')
            ->when($request->search, function ($query, $search) {
                $query->where('value', 'LIKE', '%'.$search.'%');
            })
            ->paginate(10);
    }
}
