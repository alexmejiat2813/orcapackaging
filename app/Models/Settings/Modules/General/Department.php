<?php

namespace App\Models\Settings\Modules\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Production\Requis\Requis;

class Department extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Department';
    protected $primaryKey = 'Department_ID';
    public $timestamps = false;

    protected $fillable = [
        'Department_Code',
        'Department_Description',
        'Department_Active',
    ];

    public function requis()
    {
        return $this->hasMany(Requis::class, 'Requis_Department_Id');
    }
}
