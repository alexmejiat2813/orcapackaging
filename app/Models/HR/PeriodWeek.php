<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class PeriodWeek extends Model
{
    protected $table = 'Period_Week';
    protected $primaryKey = 'Period_Week_Id';
    public $timestamps = false;

    protected $dates = ['Period_Week_StartDate', 'Period_Week_EndDate'];
}

?>
