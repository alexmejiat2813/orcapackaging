<?php

namespace App\Models\Settings\Requis;
namespace App\Models\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;

class RequisPlanifiedFrom extends Model
{
    protected $table = 'Requis_Planified_From';
    protected $primaryKey = 'Requis_Planified_From_ID';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Id',
        'Equipment_Regroupement_ID'
    ];

    public function requis()
    {
        return $this->belongsTo(Requis::class, 'Requis_Id', 'Requis_Id');
    }

    public function equipmentGroup()
    {
        return $this->belongsTo(EquipmentRegroupement::class, 'Equipment_Regroupement_ID', 'Equipment_Regroupement_ID');
    }
}
