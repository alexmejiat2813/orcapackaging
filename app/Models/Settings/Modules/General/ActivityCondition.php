<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\General\Activity;
use App\Models\Settings\Modules\Production\ProductionStatus;

class ActivityCondition extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Activity_Condition';
    protected $primaryKey = 'Activity_Condition_Id';
    public $timestamps = false;

    protected $fillable = [
        'Activity_Id',
        'Follow_Condition_Id',
        'Activity_Condition_Active',
        'Production_Status_Id',
    ];

    protected $casts = [
        'Activity_Condition_Active' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'Activity_Id', 'Activity_ID');
    }

    public function productionStatus()
    {
        return $this->belongsTo(ProductionStatus::class, 'Production_Status_Id', 'Production_Status_Id');
    }

    public function scopeActive($query)
    {
        return $query->where('Activity_Condition_Active', 1);
    }
}
