<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Return a list of products (base inks or pigments).
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔎 Opcional: filtrado si solo se quieren activos o tipo tinta
        if ($request->has('ink_only') && $request->ink_only) {
            $query->where('ProductType_ID', 10); // ejemplo: 5 = tintas
        }

        if ($request->has('q')) {
            $q = $request->get('q');
            $query->where('PrNumber', 'like', "%$q%");
            $query->where('PrActif', '=', "1");
        }

        $products = $query
            ->orderBy('PrDescription1')
            ->get(['Product_ID as id', 'PrDescription1 as text']);

        return response()->json($products);
    }

    /**
     * Get single product by name or ID
     */
    public function show($id)
    {
        $product = Product::select('Product_ID as id', 'PrDescription1 as text')
            ->where('Product_ID', $id)
            ->firstOrFail();

        return response()->json($product);
    }
}
