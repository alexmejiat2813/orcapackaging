<?php

namespace App\Models\Settings\Modules\Requis;

use Illuminate\Database\Eloquent\Model;

class FollowCondition extends Model
{
    protected $table = 'Follow_Condition';
    protected $primaryKey = 'Follow_Condition_Id';
    public $timestamps = false;

    public function requisConditions()
    {
        return $this->hasMany(RequisCondition::class, 'Follow_Condition_Id');
    }
}
