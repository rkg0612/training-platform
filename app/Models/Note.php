<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Note.
 *
 * @property int $id
 * @property int $user_id
 * @property int $unit_id
 * @property string $value
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Unit|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereValue($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'value',
    ];

    protected $with = [
        'author',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class);
    }

    public static function fetchNotes($userList, $unitId)
    {
        return self::whereIn('user_id', $userList)
            ->where('unit_id', $unitId)
            ->orderByDesc('created_at')
            ->paginate(5);
    }
}
