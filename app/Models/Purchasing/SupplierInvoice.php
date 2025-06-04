<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\Receiving;
use App\Models\Purchasing\SupplierInvoiceDetail;

class SupplierInvoice extends Model
{
    protected $table = 'Supplier_Invoice';
    protected $primaryKey = 'Supplier_Invoice_Id';
    public $timestamps = false;

    public function receiving()
    {
        return $this->belongsTo(Receiving::class, 'Receiving_ID');
    }

    public function details()
    {
        return $this->hasMany(SupplierInvoiceDetail::class, 'Supplier_Invoice_Id');
    }
}
