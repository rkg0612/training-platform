<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignedModuleUnit.
 *
 * @property-read \App\Models\AssignedModule $assignedModule
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModuleUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModuleUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedModuleUnit query()
 * @mixin \Eloquent
 */
class AssignedModuleUnit extends Model
{
    protected $fillable = [
        'assigned_module_id', 'unit_id',
    ];

    protected $with = [
        'unit', 'assignedModule',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function assignedModule()
    {
        return $this->belongsTo(AssignedModule::class);
    }
}
