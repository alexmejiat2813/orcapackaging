<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\PO;
use App\Models\Purchasing\ReceivingDetail;
use App\Models\Purchasing\Product;

class PODetail extends Model
{
    protected $table = 'PO_Detail';
    protected $primaryKey = 'PO_Detail_ID';
    public $timestamps = false;

    public function po()
    {
        return $this->belongsTo(PO::class, 'PO_ID');
    }

    public function details()
    {
        return $this->hasMany(ReceivingDetail::class, 'Receiving_ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID');
    }

}
