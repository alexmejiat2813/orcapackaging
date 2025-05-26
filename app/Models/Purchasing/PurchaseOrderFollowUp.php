<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderFollowUp extends Model
{
    protected $table = 'View_Purchase_Order_FollowUp';
    protected $primaryKey = null; // ya que es una vista sin PK
    public $incrementing = false;
    public $timestamps = false;
}

