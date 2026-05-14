<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\General\Department;
use App\Models\Settings\Modules\General\ActivityCondition;

class Activity extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Activity';
    protected $primaryKey = 'Activity_ID';
    public $timestamps = false;

    protected $fillable = [
        'Activity_Code',
        'Activity_Description',
        'Activity_DesEnglish',
        'Activity_CmdMandatory',
        'Activity_EquipMandatory',
        'Activity_MatMandatory',
        'Activity_ModifyCostEnabled',
        'Activity_Active',
        'Activity_QtyProducedMandatory',
        'Activity_TimeStamp',
        'Activity_Departement_Id',
        'Activity_isFollow',
        'Activity_Paye',
        'Activity_Type_Id',
        'Activity_Ferie',
        'Activity_IsBillable',
        'Activity_Color_Prerequisites_Missing',
        'Activity_Shift_Allow_Overflow',
        'Activity_Shift_Overflow_Automatic',
    ];

    protected $casts = [
        'Activity_CmdMandatory'         => 'boolean',
        'Activity_EquipMandatory'        => 'boolean',
        'Activity_MatMandatory'          => 'boolean',
        'Activity_ModifyCostEnabled'     => 'boolean',
        'Activity_Active'                => 'boolean',
        'Activity_QtyProducedMandatory'  => 'boolean',
        'Activity_isFollow'              => 'boolean',
        'Activity_Paye'                  => 'boolean',
        'Activity_Ferie'                 => 'boolean',
        'Activity_IsBillable'            => 'boolean',
        'Activity_Shift_Allow_Overflow'  => 'boolean',
        'Activity_Shift_Overflow_Automatic' => 'boolean',
        'Activity_TimeStamp'             => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'Activity_Departement_Id', 'Department_ID');
    }

    public function conditions()
    {
        return $this->hasMany(ActivityCondition::class, 'Activity_Id', 'Activity_ID');
    }

    public function scopeActive($query)
    {
        return $query->where('Activity_Active', 1);
    }

    public function scopeBillable($query)
    {
        return $query->where('Activity_IsBillable', 1);
    }
}
