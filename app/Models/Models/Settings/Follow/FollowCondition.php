<?php

namespace App\Models\Models\Settings\Requis;

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
