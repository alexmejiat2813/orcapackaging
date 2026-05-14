<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\Receiving;
use App\Models\Purchasing\SupplierInvoiceDetail;

class SupplierInvoice extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Supplier_Invoice';
    protected $primaryKey = 'Supplier_Invoice_Id';
    public $timestamps = false;

    protected $fillable = [
        'Receiving_ID',
        'Supplier_Invoice_Number',
        'Supplier_Invoice_Date',
        'Supplier_Invoice_Total',
    ];

    public function receiving()
    {
        return $this->belongsTo(Receiving::class, 'Receiving_ID');
    }

    public function details()
    {
        return $this->hasMany(SupplierInvoiceDetail::class, 'Supplier_Invoice_Id');
    }
}
