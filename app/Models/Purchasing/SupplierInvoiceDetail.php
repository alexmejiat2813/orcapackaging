<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\SupplierInvoice;
use App\Models\Purchasing\ReceivingDetail;

class SupplierInvoiceDetail extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Supplier_Invoice_Detail';
    protected $primaryKey = 'Supplier_Invoice_Detail_Id';
    public $timestamps = false;

    protected $fillable = [
        'Supplier_Invoice_Id',
        'Receiving_Detail_ID',
        'Quantity',
        'Unit_Price',
        'Line_Total',
    ];

    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'Supplier_Invoice_Id');
    }

    public function receivingDetail()
    {
        return $this->belongsTo(ReceivingDetail::class, 'Receiving_Detail_ID');
    }
}
