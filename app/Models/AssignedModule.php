<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignedModule.
 *
 * @property int $id
 * @property int $module_id
 * @property int $user_id
 * @property int $assignee_id
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $shared_by
 * @property-read \App\Models\User $assignee
 * @property-read \App\Models\Module $module
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereAssigneeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereSharedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModule whereUserId($value)
 * @mixin \Eloquent
 */
class AssignedModule extends Model
{
    protected $fillable = [
        'module_id', 'user_id', 'due_date', 'assignee_id',
    ];

    protected $dates = [
        'due_date',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }
}
