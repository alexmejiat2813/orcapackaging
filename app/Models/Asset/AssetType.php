<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;

use App\Models\Asset\Asset;

class AssetType extends Model
{
    protected $table = 'Asset_Type';
    protected $primaryKey = 'Asset_Type_ID';
    public $timestamps = false;

    protected $fillable = ['Asset_Type_Code', 'Asset_Type_Description'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'Asset_Type_Id');
    }
}
