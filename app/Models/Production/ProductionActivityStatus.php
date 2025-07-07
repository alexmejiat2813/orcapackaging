<?php

namespace App\Models\Production;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductionActivityStatus extends Model
{
    public static function getByDate(string $date)
    {
        return DB::select('EXEC GetProductionActivityByDate @DateNow = ?', [$date]);
    }
}
