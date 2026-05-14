<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\Receiving;
use App\Models\Purchasing\SupplierInvoiceDetail;
use App\Models\Purchasing\SupplierInvoice;
use App\Models\Product\Product;

class ReceivingDetail extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Receiving_Detail';
    protected $primaryKey = 'Receiving_Detail_ID';
    public $timestamps = false;

    protected $fillable = [
        'Receiving_ID',
        'Product_ID',
        'Quantity',
        'Unit_Price',
        'Line_Total',
    ];

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
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    public function invoices()
    {
        return $this->hasManyThrough(
            SupplierInvoice::class,
            SupplierInvoiceDetail::class,
            'Receiving_Detail_ID',
            'Supplier_Invoice_Id',
            'Receiving_Detail_ID',
            'Supplier_Invoice_Id'
        );
    }
}
