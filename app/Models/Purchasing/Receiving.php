<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\PO;
use App\Models\Purchasing\ReceivingDetail;
use App\Models\Purchasing\SupplierInvoice;


class Receiving extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Receiving';
    protected $connection = 'sqlsrv';
    protected $primaryKey = 'Receiving_ID';
    public $timestamps = false;

    public function po()
    {
        return $this->belongsTo(PO::class, 'PO_ID');
    }

    public function details()
    {
        return $this->hasMany(ReceivingDetail::class, 'Receiving_ID');
    }

    public function invoices()
    {
        return $this->hasOne(SupplierInvoice::class, 'Receiving_ID');
    }
}
