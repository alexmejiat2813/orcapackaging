<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'ProductType';
    protected $primaryKey = 'ProductType_ID';
    public $timestamps = false;

}
