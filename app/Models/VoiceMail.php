<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VoiceMail.
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoiceMail whereValue($value)
 * @mixin \Eloquent
 */
class VoiceMail extends Model
{
    protected $fillable = [
        'name', 'value', 'remarks',
    ];
}
