<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;

use App\Models\Asset\Asset;
use App\Models\Supplier\Supplier;


class AssetLink extends Model
{
    protected $table = 'Asset_Link';
    protected $primaryKey = 'Asset_Link_ID';
    public $timestamps = false;

    protected $fillable = ['Asset_ID', 'Customer_ID', 'Supplier_ID', 'Commission_Pct', 'is_Default'];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'Asset_ID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID');
    }
}
