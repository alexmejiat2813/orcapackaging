<?php

namespace App\Models\Settings\Modules\Production\Requis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Production\Requis\Requis;
use App\Models\Settings\Modules\Production\ProductionStatus;

class RequisProductionStatus extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Requis_Production_Status';
    protected $primaryKey = 'Requis_Production_Status_Id';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Id',
        'Production_Status_Id',
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
