<?php

namespace App\Models\Settings\Modules\Follow;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Production\Requis\RequisCondition;

class FollowCondition extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Follow_Condition';
    protected $primaryKey = 'Follow_Condition_Id';
    public $timestamps = false;

    public function requisConditions()
    {
        return $this->hasMany(RequisCondition::class, 'Follow_Condition_Id');
    }
}
