<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier\Supplier;
use App\Models\Purchasing\PODetail;
use App\Models\Purchasing\Receiving;

class PO extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PO';
    protected $primaryKey = 'PO_ID';
    public $timestamps = true;

    protected $fillable = [
        'PO_Number',
        'PO_Date',
        'Supplier_ID',
        'PO_Status',
        'PO_Total',
    ];

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
