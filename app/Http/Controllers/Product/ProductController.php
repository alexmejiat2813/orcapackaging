<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('ink_only') && $request->ink_only) {
            $query->where('ProductType_ID', 10);
        }

        if ($request->has('q')) {
            $q = $request->get('q');
            $query->where(function ($q2) use ($q) {
                $q2->where('PrNumber', 'like', "%{$q}%")
                   ->orWhere('PrDescription1', 'like', "%{$q}%");
            })->where('PrActif', 1);
        }

        return response()->json(
            $query->orderBy('PrDescription1')
                  ->get(['Product_ID as id', 'PrNumber', 'PrDescription1 as text'])
        );
    }

    public function show($id)
    {
        $product = Product::with('productType')
            ->where('Product_ID', $id)
            ->firstOrFail();

        if (request()->expectsJson()) {
            return response()->json($product);
        }

        return view('inventory.product-show', compact('product'));
    }

    public function showByType($type)
    {
        $productType = ProductType::where('ProductType_Code', $type)
            ->orWhere('ProductType_ID', $type)
            ->first();

        if (!$productType) {
            abort(404);
        }

        $products = Product::where('ProductType_ID', $productType->ProductType_ID)
            ->orderBy('PrNumber')
            ->get([
                'Product_ID', 'PrNumber', 'PrDescription1', 'PrDescription2',
                'PrActif', 'PrStock', 'PrMinQty', 'PrMaxQty', 'Price_1',
                'ProductType_ID', 'PrLocation'
            ]);

        return view('inventory.product', compact('products', 'productType'));
    }

    public function getProductsByType($type)
    {
        $productType = ProductType::where('ProductType_Code', $type)
            ->orWhere('ProductType_ID', $type)
            ->first();

        if (!$productType) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        return response()->json(
            Product::where('ProductType_ID', $productType->ProductType_ID)
                ->orderBy('PrNumber')
                ->get()
        );
    }

    public function catalog(Request $request)
    {
        $types = ProductType::orderBy('ProductType_Description')->get();

        $query = Product::query();

        if ($request->filled('type')) {
            $query->where('ProductType_ID', $request->type);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($q2) use ($q) {
                $q2->where('PrNumber', 'like', "%{$q}%")
                   ->orWhere('PrDescription1', 'like', "%{$q}%");
            });
        }

        if ($request->filled('active')) {
            $query->where('PrActif', (int) $request->active);
        }

        $products = $query->orderBy('PrNumber')->paginate(50)->withQueryString();

        return view('inventory.catalog', compact('products', 'types'));
    }
}
