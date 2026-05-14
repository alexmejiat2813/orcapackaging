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
    protected $connection = 'sqlsrv';
    protected $table = 'Period_Week';
    protected $primaryKey = 'Period_Week_Id';
    public $timestamps = false;

    protected $casts = [
        'Period_Week_StartDate' => 'datetime',
        'Period_Week_EndDate'   => 'datetime',
    ];
}

?>
