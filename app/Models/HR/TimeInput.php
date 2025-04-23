<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class TimeInput extends Model
{
    protected $table = 'TimeInput'; // o 'dbo.TimeInput' si es necesario con el esquema
    protected $primaryKey = 'TimeInput_ID';
    public $timestamps = false; // Si la tabla no tiene created_at / updated_at

    protected $fillable = [
        'Users_ID',
        'Activity_ID',
        'TimeInput_StartTime',
        'TimeInput_EndTime',
        'TimeInput_Comment',
        'TimeInput_Time',
        'TimeInput_TimeInHour',
        'TimeInput_IsStart',
        'TimeInput_Approved',
        'TimeInput_TimeStamp',
        'TimeInput_StartTimeCorrected',
        'TimeInput_EndTimeCorrected',
        'is_Punch_Clock',
        'Period_Week_Id',
        'TimeInput_Last_Delay_EndTime',
        'TimeInput_Last_EndTime'
    ];

    protected $casts = [
        'TimeInput_StartTime' => 'datetime',
        'TimeInput_EndTime' => 'datetime',
        'TimeInput_TimeStamp' => 'datetime',
        'TimeInput_StartTimeCorrected' => 'datetime',
        'TimeInput_EndTimeCorrected' => 'datetime',
        'TimeInput_Last_EndTime' => 'datetime',
        'TimeInput_Comment' => 'string',
        'TimeInput_IsStart' => 'boolean',
        'TimeInput_Approved' => 'boolean',
        'is_Punch_Clock' => 'boolean',
        'TimeInput_Time' => 'integer',
        'TimeInput_TimeInHour' => 'float',
        'TimeInput_Last_Delay_EndTime' => 'integer',
    ];



    // 🔁 Relación con tabla Users (opcional pero recomendado)
    public function user()
    {
        return $this->belongsTo(Users::class, 'Users_ID', 'Users_ID');
    }

    public function period()
{
    return $this->belongsTo(PeriodWeek::class, 'Period_Week_Id');
}
}
