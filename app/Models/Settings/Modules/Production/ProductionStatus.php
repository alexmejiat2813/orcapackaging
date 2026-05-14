<?php

namespace App\Models\Settings\Modules\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionStatus extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Production_Status';
    protected $primaryKey = 'Production_Status_Id';
    public $timestamps = false;

    protected $fillable = [
        'Production_Status_Code',
        'Production_Status_Description',
        'Production_Status_Description_English',
        'Production_Status_Active',
        'Production_Status_Order',
    ];

    protected $casts = [
        'Production_Status_Active' => 'boolean',
    ];

    public function requisProductionStatuses()
    {
        return $this->hasMany(\App\Models\Settings\Modules\Production\Requis\RequisProductionStatus::class, 'Production_Status_Id');
    }

    public function requisConditions()
    {
        return $this->hasMany(\App\Models\Settings\Modules\Production\Requis\RequisCondition::class, 'Production_Status_Id');
    }

    public function statusCompletes()
    {
        return $this->hasMany(\App\Models\Settings\Modules\Production\Requis\RequisProductionStatusComplete::class, 'Follow_Production_Status_Id');
    }

    public function scopeActive($query)
    {
        return $query->where('Production_Status_Active', 1)->orderBy('Production_Status_Order');
    }
}
