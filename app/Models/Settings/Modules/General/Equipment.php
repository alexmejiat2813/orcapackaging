<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\General\Department;

class Equipment extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Equipment';
    protected $primaryKey = 'Equipment_ID';
    public $timestamps = false;

    protected $fillable = [
        'Equipment_Code',
        'Equipment_Description',
        'Equipment_CostRate',
        'Equipment_Default_Unit_Measurement_ID',
        'Equipment_IsActive',
        'Equipment_TimeStamp',
        'Equipment_DescEnglish',
        'Department_ID',
        'Equipement_Fix_Rate',
        'Equipement_Ink_Rate',
        'Equipment_Variable_Rate',
        'Equipment_Other_Rate1',
        'Equipment_Other_Rate2',
        'Equipment_isFollow',
        'Equipment_Color_Prerequisites_Missing',
        'Equipment_Shift_Allow_Overflow',
        'Equipment_Shift_Overflow_Automatic',
        'Location_ID',
        'Cost_Center_ID',
        'Equipment_Reservation_Visible',
        'Calendar_Time_Scale'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'Department_ID', 'Department_ID');
    }

    public static function getDescriptionById($id)
    {
        return self::where('Equipment_ID', $id)->value('Equipment_Description') ?? 'Unknown';
    }

    public static function getSchedulerResources()
    {
        return self::select(
                'Equipment_ID',
                'Equipment_Description as calendar'
            )
            ->where('Equipment_IsActive', true)
            ->get(); // ← sin argumentos aquí
    }
}
