<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;

class UnitMeasurement extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Unit_Measurement';
    protected $primaryKey = 'Unit_Measurement_Id';
    public $timestamps = false;

    protected $fillable = [
        'Unit_Measurement_Code',
        'Unit_Measurement_Description',
        'Unit_Measurement_Active',
    ];

    protected $casts = [
        'Unit_Measurement_Active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('Unit_Measurement_Active', 1);
    }
}
