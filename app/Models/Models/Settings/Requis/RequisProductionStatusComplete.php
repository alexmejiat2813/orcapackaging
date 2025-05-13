<?php

namespace App\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Follow\FollowType;
use App\Models\Settings\Production\ProductionStatus;
use App\Models\Settings\Requis\Requis;

class RequisProductionStatusComplete extends Model
{
    protected $table = 'Requis_Production_status_complete';
    protected $primaryKey = 'Requis_Production_Status_Complete_Id';
    public $timestamps = false;

    protected $fillable = [
        'Closed_Operation_Id',
        'Follow_Type_Id',
        'Follow_Operation_Id',
        'Follow_Production_Status_Id',
        'Closed_Type_Id'
    ];

    public function followType()
    {
        return $this->belongsTo(FollowType::class, 'Follow_Type_Id');
    }

    public function closedType()
    {
        return $this->belongsTo(FollowType::class, 'Closed_Type_Id');
    }

    public function followProductionStatus()
    {
        return $this->belongsTo(ProductionStatus::class, 'Follow_Production_Status_Id');
    }

    public function followOperation()
    {
        return $this->belongsTo(Requis::class, 'Follow_Operation_Id', 'Requis_Id');
    }

    public function closedOperation()
    {
        return $this->belongsTo(Requis::class, 'Closed_Operation_Id', 'Requis_Id');
    }
}
