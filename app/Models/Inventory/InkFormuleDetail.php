<?php

namespace App\Models\Inventory;

use App\Models\Inventory\InkFormule;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class InkFormuleDetail extends Model
{
    protected $fillable = ['ink_formule_id', 'product_id', 'amount_kg', 'percentage'];

    public function formule(): BelongsTo
    {
        return $this->belongsTo(InkFormule::class, 'ink_formule_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'Product_ID'); // 👈 usa claves correctas
    }
}

