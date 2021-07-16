<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\UserUnit.
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Models\User|null $assigned_by
 * @property int $unit_id
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $date_completed
 * @property int $played
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $shared_by
 * @property-read \App\Models\Unit $unit
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereAssignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereDateCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit wherePlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereSharedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserUnit whereUserId($value)
 * @mixin \Eloquent
 */
class UserUnit extends Pivot
{
    protected $table = 'user_units';

    protected $fillable = [
        'user_id', 'unit_id', 'due_date', 'date_completed', 'assigned_by',
    ];

    protected $dates = [
        'due_date', 'date_completed',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigned_by()
    {
        return $this->belongsTo('\App\Models\User', 'assigned_by', 'id');
    }
}
