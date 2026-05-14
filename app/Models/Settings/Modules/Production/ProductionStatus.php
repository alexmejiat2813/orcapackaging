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
        'Production_Status_Actif',
        'Production_Status_Quantity_Required',
        'Production_Status_Color',
        'Production_Status_Pattern',
    ];

    protected $casts = [
        'Production_Status_Actif'             => 'boolean',
        'Production_Status_Quantity_Required' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('Production_Status_Actif', 1);
    }
}
