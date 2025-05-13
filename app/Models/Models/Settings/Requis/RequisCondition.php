<?php

namespace App\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Follow\FollowType;
use App\Models\Settings\Follow\FollowCondition;
use App\Models\Settings\Production\ProductionStatus;
use App\Models\Settings\Requis\Requis;

class RequisCondition extends Model
{
    protected $table = 'Requis_Condition';
    protected $primaryKey = 'Requis_Condition_Id';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Id',
        'Follow_Condition_Id',
        'Requis_Condition_Active',
        'Production_Status_Id'
    ];

    public function requis()
    {
        return $this->belongsTo(Requis::class, 'Requis_Id');
    }

    public function followCondition()
    {
        return $this->belongsTo(FollowCondition::class, 'Follow_Condition_Id');
    }

    public function productionStatus()
    {
        return $this->belongsTo(ProductionStatus::class, 'Production_Status_Id');
    }
}

