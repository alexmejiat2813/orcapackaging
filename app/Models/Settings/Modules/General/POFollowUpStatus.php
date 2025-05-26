<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Requis\Requis;

class POFollowUpStatus extends Model
{
    protected $table = 'PO_Followup_Status';
    protected $primaryKey = 'Status_ID';
    public $timestamps = false;

    protected $fillable = [
        'Status_Name',
    ];

    public function followups()
    {
        return $this->hasMany(POFulfillUpLog::class, 'Status_ID');
    }
}
