<?php

namespace App\Models\Settings\Modules\Requis;

use Illuminate\Database\Eloquent\Model;

class FollowType extends Model
{
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
