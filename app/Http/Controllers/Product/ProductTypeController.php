<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller {
    public function index() {
        // Solo los product types útiles para selección de producto
        $types = ProductType::query()->orderBy('ProductType_Orderby')->get();

        return view('inventory.types', compact('types'));
    }
}
