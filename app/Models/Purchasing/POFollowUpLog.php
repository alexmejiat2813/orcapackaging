<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\HR\Users;
use App\Models\Settings\Modules\General\POFollowUpStatus;

class POFollowUpLog extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PO_FollowUp_Log';
    protected $primaryKey = 'PO_FollowUp_ID';
    public $timestamps = false;

    protected $fillable = [
        'PO_ID',
        'Followup_Date',
        'Followup_By',
        'Followup_Notes',
        'Status_ID',
    ];

    public function po()
    {
        return $this->belongsTo(PO::class, 'PO_ID');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'Followup_By', 'Users_ID');
    }

    public function status()
    {
        return $this->belongsTo(POFollowUpStatus::class, 'Status_ID');
    }
}
