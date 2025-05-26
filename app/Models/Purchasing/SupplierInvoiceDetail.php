<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\invoice;
use App\Models\Purchasing\receivingDetail;

class SupplierInvoiceDetail extends Model
{
    protected $table = 'Supplier_Invoice_Detail';
    protected $primaryKey = 'Supplier_Invoice_Detail_Id';
    public $timestamps = false;

    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'Supplier_Invoice_Id');
    }

    public function receivingDetail()
    {
        return $this->belongsTo(ReceivingDetail::class, 'Receiving_Detail_ID');
    }

}
