<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'Supplier';
    protected $primaryKey = 'Supplier_ID';
    public $timestamps = false;

    public function purchaseOrders()
    {
        return $this->hasMany(PO::class, 'Supplier_ID');
    }
}
