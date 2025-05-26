<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $table = 'Product';
    protected $primaryKey = 'Product_ID';
    public $timestamps = false;
}
