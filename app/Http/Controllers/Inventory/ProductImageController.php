<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductImageController extends Controller {
    public function index()
    {
        $clients = DB::table('Customer')->select('Customer_No', 'Customer_Name')->get();
        $products = DB::table('Product')->select('PrNumber')->get();
        return view('inventory.productImage', compact('clients', 'products'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'client' => 'required|string',
            'product' => 'required|string'
        ]);

        $file = $request->file('image');
        $client = $request->input('client');
        $product = $request->input('product');

        $folder = "images/{$client}";

        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($folder, $filename);

        if (!$path) {
            return response()->json([
                'success' => false,
                'message' => "Échec de l'enregistrement de l'image. Veuillez réessayer."
            ], 500); // 500 = erreur serveur
        }

        DB::table('Product')
            ->where('PrNumber', "{$product}")
            ->update(['PrPath' => '\\\\192.168.0.97\\storage\\app\\private\\{$folder}']);

        return response()->json([
            'success' => true,
            'message' => "Image enregistrée dans /storage/app/private/{$folder} avec succès !"
        ]);
    }
}