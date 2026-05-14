<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;

use App\Models\Asset\AssetType;
use App\Models\Asset\AssetLink;

class Asset extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Asset';
    protected $primaryKey = 'Asset_ID';
    public $timestamps = false;

    protected $fillable = [
        'Asset_Code', 'Asset_Active', 'Asset_Type_Id', 'Asset_FName', 'Asset_Name', 'SRCareOf',
        'SRAddress', 'SRCity', 'SRPostalCode', 'SRPhoneNumber1', 'SRPhoneNumber2', 'SRWebAddress',
        'SREMail', 'SREMail2', 'SRLanguage'
    ];

    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'Asset_Type_Id');
    }

    public function assetLinks()
    {
        return $this->hasMany(AssetLink::class, 'Asset_ID');
    }
}
