<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class TimeInput extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'TimeInput';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'TimeInput_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        'TimeInput_Last_EndTime',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'TimeInput_StartTime'              => 'datetime',
        'TimeInput_EndTime'                => 'datetime',
        'TimeInput_TimeStamp'             => 'datetime',
        'TimeInput_StartTimeCorrected'    => 'datetime',
        'TimeInput_EndTimeCorrected'      => 'datetime',
        'TimeInput_Last_EndTime'          => 'datetime',
        'TimeInput_Comment'               => 'string',
        'TimeInput_IsStart'               => 'boolean',
        'TimeInput_Approved'              => 'boolean',
        'is_Punch_Clock'                  => 'boolean',
        'TimeInput_Time'                  => 'integer',
        'TimeInput_TimeInHour'            => 'float',
        'TimeInput_Last_Delay_EndTime'    => 'integer',
    ];

    /**
     * Relationship with the Users table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Users::class, 'Users_ID', 'Users_ID');
    }

    /**
     * Relationship with the PeriodWeek table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
        return $this->belongsTo(PeriodWeek::class, 'Period_Week_Id');
    }
}
?>
