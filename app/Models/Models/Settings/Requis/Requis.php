<?php

namespace App\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Requis extends Model
{
    protected $table = 'Requis';
    protected $primaryKey = 'Requis_Id';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Code',
        'Requis_Description',
        'Requis_Description_English',
        'Requis_Department_Id',
        'Requis_isFollow',
        'Requis_Actif',
        'Color_Prerequisites_Missing',
        'requis_isplanification'
    ];

    /**
     * Relationship to Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'Requis_Department_Id', 'Department_ID');
    }

    /**
     * Relationship to Production Statuses
     */
    public function productionStatuses()
    {
        return $this->hasMany(RequisProductionStatus::class, 'Requis_Id', 'Requis_Id');
    }

    /**
     * Relationship to Requis Conditions
     */
    public function conditions()
    {
        return $this->hasMany(RequisCondition::class, 'Requis_Id', 'Requis_Id');
    }

    /**
     * Relationship to Estimations
     */
    public function estimations()
    {
        return $this->hasMany(RequisEstimation::class, 'Requis_Id', 'Requis_Id');
    }

    /**
     * Relationship to Planified From
     */
    public function planifiedFrom()
    {
        return $this->hasMany(RequisPlanifiedFrom::class, 'Requis_Id', 'Requis_Id');
    }

    /**
     * Relationship to Production Status Completion
     */
    public function productionStatusCompletion()
    {
        return $this->hasMany(RequisProductionStatusComplete::class, 'Follow_Operation_Id', 'Requis_Id');
    }
} 
