<?php

namespace App\Models\Settings\Requis;
namespace App\Models\Models\Settings\Requis;

use Illuminate\Database\Eloquent\Model;

class RequisEstimation extends Model
{
{
    protected $table = 'Requis_Estimation';
    protected $primaryKey = 'Requis_Estimation_Id';
    public $timestamps = false;

    protected $fillable = [
        'Requis_Id',
        'Estimation_Value',
        'Estimation_Type'
    ];

    public function requis()
    {
        return $this->belongsTo(Requis::class, 'Requis_Id', 'Requis_Id');
    }
}
