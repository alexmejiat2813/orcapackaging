<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class PeriodWeek extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Period_Week';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'Period_Week_Id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to date objects.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'Period_Week_StartDate',
        'Period_Week_EndDate',
    ];
}

?>
