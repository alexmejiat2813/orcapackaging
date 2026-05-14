<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\POFollowUpLog;

class POFollowUpStatus extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PO_Followup_Status';
    protected $primaryKey = 'Status_ID';
    public $timestamps = false;

    protected $fillable = [
        'Status_Name',
    ];

    public function followups()
    {
        return $this->hasMany(POFollowUpLog::class, 'Status_ID');
    }
}
