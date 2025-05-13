<?php

namespace App\Models\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;

class ProductionStatus extends Model
{
    protected $table = 'Production_Status';
    protected $primaryKey = 'Production_Status_Id';
    public $timestamps = false;

    public function requisProductionStatuses()
    {
        return $this->hasMany(RequisProductionStatus::class, 'Production_Status_Id');
    }

    public function requisConditions()
    {
        return $this->hasMany(RequisCondition::class, 'Production_Status_Id');
    }

    public function statusCompletes()
    {
        return $this->hasMany(RequisProductionStatusComplete::class, 'Follow_Production_Status_Id');
    }
}
