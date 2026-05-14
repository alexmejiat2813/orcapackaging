<?php

namespace App\Models\Settings\Modules\Production\Requis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Production\Requis\Requis;

class RequisPlanifiedFrom extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Requis_Planified_From';
    protected $primaryKey = 'Requis_Planified_From_ID';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Id',
        'Equipment_Regroupement_ID',
    ];

    public function requis()
    {
        return $this->belongsTo(Requis::class, 'Requis_Id', 'Requis_Id');
    }
}
