<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\Supplier;
use App\Models\Purchasing\PODetail;
use App\Models\Purchasing\Receiving;

class PO extends Model
{
    protected $table = 'PO';
    protected $primaryKey = 'PO_ID';
    public $timestamps = false;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'Supplier_ID');
    }

    public function details()
    {
        return $this->hasMany(PODetail::class, 'PO_ID');
    }

    public function receptions()
    {
        return $this->hasMany(Receiving::class, 'PO_ID');
    }
}
