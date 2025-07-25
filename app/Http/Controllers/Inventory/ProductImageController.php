<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductImageController extends Controller {
    public function index()
    {
        $products = DB::table('Product')->select('PrNumber')->get();
        return view('inventory.productImage', compact( 'products'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'product' => 'required|string'
        ]);

        $file = $request->file('image');
        $product = $request->input('product');

        $folder = "images";

        $filename = Str::slug($product, '_') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => "Échec de l'enregistrement de l'image. Veuillez réessayer."
            ], 500); // 500 = erreur serveur
        }

        DB::table('Product')
            ->where('PrNumber', "{$product}")
            ->update(['PrPath' => "\\\\192.168.0.97\\storage\\app\\public\\{$path}"]);

        return response()->json([
            'success' => true,
            'message' => "Image enregistrée {$path} avec succès !"
        ]);
    }
}