<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\Receiving;
use App\Models\Purchasing\invoiceDetails;
use App\Models\Purchasing\product;

class ReceivingDetail extends Model
{
    protected $table = 'Receiving_Detail';
    protected $primaryKey = 'Receiving_Detail_ID';
    public $timestamps = false;

    public function receiving()
    {
        return $this->belongsTo(Receiving::class, 'Receiving_ID');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(SupplierInvoiceDetail::class, 'Receiving_Detail_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID');
    }

    public function invoices()
    {
        return $this->hasManyThrough(
            SupplierInvoice::class,
            SupplierInvoiceDetail::class,
            'Receiving_Detail_ID',           // Foreign key en SupplierInvoiceDetail
            'Supplier_Invoice_Id',    // Foreign key en SupplierInvoice
            'Receiving_Detail_ID',           // Local key en Receiving
            'Supplier_Invoice_Id'     // Local key en SupplierInvoiceDetail
        );
    }

}
