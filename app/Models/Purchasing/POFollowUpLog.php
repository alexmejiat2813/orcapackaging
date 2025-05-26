<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Settings\Modules\General\POFollowUpStatus;

class POFollowUpLog extends Model
{
    protected $table = 'PO_FollowUp_Log';
    protected $primaryKey = 'PO_FollowUp_ID';
    public $timestamps = false;

    protected $fillable = [
        'PO_ID',
        'Followup_Date',
        'Followup_By',
        'Followup_Notes',
        'Status_ID'
    ];

    // Relación con PO
    public function po()
    {
        return $this->belongsTo(PO::class, 'PO_ID');
    }

    // Relación con User (si Followup_By es un user_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'Followup_By', 'Users_ID');
    }

    // Relación con el estado (si tienes una tabla de estados)
    public function status()
    {
        return $this->belongsTo(POFollowUpStatus::class, 'Status_ID');
    }
}
