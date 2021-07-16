<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserAnsweredQuiz.
 *
 * @property int $id
 * @property int $user_id
 * @property int $quiz_id
 * @property int $total_score
 * @property int $total_number_of_questions
 * @property string|null $quiz_form
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $unit_id
 * @property-read \App\Models\Quiz $quiz
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereQuizForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereTotalNumberOfQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereTotalScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnsweredQuiz whereUserId($value)
 * @mixin \Eloquent
 */
class UserAnsweredQuiz extends Model
{
    public $fillable = [
        'user_id', 'quiz_id', 'quiz_form', 'total_score', 'total_number_of_questions', 'unit_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
