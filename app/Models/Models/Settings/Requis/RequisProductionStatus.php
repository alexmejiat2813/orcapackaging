<?php

namespace App\Models\Settings\Requis;
namespace App\Models\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;

class RequisProductionStatus extends Model
{
    protected $table = 'Requis_Production_Status';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Id',
        'Production_Status_Id'
    ];

    public function requis()
    {
        return $this->belongsTo(Requis::class, 'Requis_Id', 'Requis_Id');
    }

    public function status()
    {
        return $this->belongsTo(ProductionStatus::class, 'Production_Status_Id', 'Production_Status_Id');
    }
}
