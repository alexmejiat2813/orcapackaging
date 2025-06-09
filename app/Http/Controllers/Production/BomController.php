<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Production\Commande;
use App\Models\Production\CommandeReceipe;
use App\Models\Production\CommandeQuotationReceipe;
use  App\Models\Product\Product;

class BomController extends Controller
{

public function index()
{
    $requests = Commande::all();
    $products = Product::where('PrActif', 1)->orderBy('PrNumber')->get(); // solo productos activos
    return view('production.bom', compact('requests', 'products'));
}

public function searchProducts(Request $request)
{
    $search = $request->query('term');

    $products = Product::where('PrActif', 1)
        ->where(function ($query) use ($search) {
            $query->where('PrNumber', 'LIKE', "%$search%")
                  ->orWhere('PrDescription1', 'LIKE', "%$search%");
        })
        ->orderBy('PrNumber')
        ->limit(20)
        ->get();

    $results = $products->map(function ($product) {
        return [
            'id' => $product->Product_ID,
            'text' => $product->PrNumber . ' - ' . $product->PrDescription1
        ];
    });

    return response()->json($results);
}

public function getDetails($cmdid)
{
    $result = CommandeQuotationReceipe::where(function ($query) use ($cmdid) {
            $query->where('Commande_Id_CR', $cmdid)
                  ->orWhere('Commande_Id_QR', $cmdid);
        })
        ->where(function ($query) {
            $query->where('Equipment_Id', 0)
                  ->orWhereNull('Equipment_Id');
        })
        ->get();

    return response()->json($result);
}

public function saveRecipe(Request $request)
{
    $validated = $request->validate([
        'Commande_Id_CR' => 'required|integer',
        'Product_Id' => 'required|integer',
        'Quotation_Receipe_Id' => 'nullable|integer',
        'Value' => 'required|numeric',
        'Actif' => 'boolean'
    ]);

    $record = CommandeReceipe::updateOrCreate(
        [
            'Commande_Id' => $validated['Commande_Id_CR'],
            'Quotation_Receipe_Id' => $validated['Quotation_Receipe_Id']
        ],
        [
            'Product_Id' => $validated['Product_Id'],
            'Value' => $validated['Value'],
            'Actif' => $validated['Actif'] ?? true,
            'Create_By' => auth()->id(),
            'TimeStamps' => now()
        ]
    );

    return response()->json(['success' => true, 'message' => 'Saved successfully']);
}

public function deleteRecipe(Request $request)
{
    $quotationId = $request->input('Quotation_Receipe_Id');
    $commandeId = $request->input('Commande_Receipe_Id');

    if ($quotationId && $commandeId) {
        DB::table('Commande_Receipe')->where('Commande_Receipe_Id', $commandeId)->delete();
        DB::table('Quotation_Receipe')->where('Quotation_Receipe_Id', $quotationId)->delete();
    } elseif ($quotationId) {
        DB::table('Quotation_Receipe')->where('Quotation_Receipe_Id', $quotationId)->delete();
    } elseif ($commandeId) {
        DB::table('Commande_Receipe')->where('Commande_Receipe_Id', $commandeId)->delete();
    }

    return response()->json(['success' => true]);
}





    public function storeDetail(Request $request)
{
    DB::table('CommandeMaterialCheck')->insert([
        'Schedule_ID' => $request->Schedule_ID,
        'Material_Code' => $request->Material_Code,
        'Material_Quantity' => $request->Material_Quantity,
        'Unit_Measurement_ID' => $request->Unit_Measurement_ID,
        'Comment' => $request->Comment,
        'Checked' => 0,
        'Checked_By' => auth()->id(),
        'Checked_At' => now()
    ]);

    return response()->json(['success' => true]);
}

public function updateDetail($id, Request $request)
{
    DB::table('CommandeMaterialCheck')->where('MaterialCheck_ID', $id)->update([
        'Material_Quantity' => $request->Material_Quantity,
        'Comment' => $request->Comment,
        'Checked' => $request->Checked ? 1 : 0,
        'Checked_By' => auth()->id(),
        'Checked_At' => now()
    ]);

    return response()->json(['success' => true]);
}

public function deleteDetail($id)
{
    DB::table('CommandeMaterialCheck')->where('MaterialCheck_ID', $id)->delete();

    return response()->json(['success' => true]);
}

}
?>
