<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Requis\Requis;

class Department extends Model
{
    protected $table = 'Department';
    protected $primaryKey = 'Department_ID';
    public $timestamps = false;

    protected $fillable = [
        'Department_Description'
    ];

    public function requis()
    {
        return $this->hasMany(Requis::class, 'Requis_Department_Id');
    }
}
