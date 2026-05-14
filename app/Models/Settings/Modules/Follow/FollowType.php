<?php

namespace App\Models\Settings\Modules\Follow;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Production\Requis\RequisProductionStatusComplete;

class FollowType extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Follow_Type';
    protected $primaryKey = 'Follow_Type_Id';
    public $timestamps = false;

    public function follows()
    {
        return $this->hasMany(RequisProductionStatusComplete::class, 'Follow_Type_Id');
    }

    public function closures()
    {
        return $this->hasMany(RequisProductionStatusComplete::class, 'Closed_Type_Id');
    }
}
