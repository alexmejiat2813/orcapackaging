<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Inventory\InkFormule;
use App\Models\Inventory\InkFormuleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InkFormuleController extends Controller
{
    /**
     * Show all ink formulas.
     */
    public function index()
    {
        $formulas = InkFormule::with(['baseProduct', 'components.product'])->get();
        return view('ink_formulas.index', compact('formulas'));
    }

    /**
     * Show the form for creating a new formula.
     */
    public function create()
{
    $products = Product::where('ProductType_ID', 10)
                       ->where('PrActif', 1)
                       ->orderBy('PrNumber')
                       ->get();

    return view('inventory.formuleink', compact('products'));
}

    /**
     * Store a new formula.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'final_color' => 'required|string|max:255',
            'total_kg' => 'required|numeric|min:0.01',
            'components' => 'required|array|min:2',
            'components.*.product_id' => 'required|exists:Product,Product_ID',
            'components.*.kg' => 'required|numeric|min:0.01',
            'components.*.percent' => 'required|numeric|min:0.01',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $formule = InkFormule::create([
                'final_color' => $validated['final_color'],
                'total_kg' => $validated['total_kg'],
                'created_by_user_id' => Auth::id(),
            ]);

            foreach ($validated['components'] as $component) {
                InkFormuleDetail::create([
                    'ink_formule_id' => $formule->id,
                    'product_id' => $component['product_id'],
                    'amount_kg' => $component['kg'],
                    'percentage' => $component['percent'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function list()
{
    $formules = InkFormule::with('details.product') // asume relación 'details' y 'product'
        ->get()
        ->map(function ($formule) {
            return $formule->details->map(function ($detail) use ($formule) {
                return [
                    'FinalColor'   => $formule->final_color,
                    'TotalKg'      => $formule->total_kg,
                    'ProductName'  => $detail->product->PrDescription1 ?? '',
                    'ProductCode'  => $detail->product->PrNumber ?? '',
                    'Kg'           => $detail->amount_kg,
                    'Percent'      => $detail->percentage,
                ];
            });
        })
        ->flatten(1)
        ->values();

    return response()->json($formules);
}


    /**
     * Show a single formula.
     */
    public function show($id)
    {
        $formula = InkFormule::with(['baseProduct', 'components.product'])->findOrFail($id);
        return view('ink_formulas.show', compact('formula'));
    }

    /**
     * Show the form for editing a formula.
     */
    public function edit($id)
    {
        $formula = InkFormule::with('components')->findOrFail($id);
        $products = Product::all();
        return view('ink_formulas.edit', compact('formula', 'products'));
    }

    /**
     * Update a formula.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'formula_name' => 'required|string|max:100',
            'product_id' => 'required|exists:products,id',
            'total_quantity' => 'required|numeric|min:0.01',
            'components' => 'required|array',
            'components.*.product_id' => 'required|exists:products,id',
            'components.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $id) {
            $formula = InkFormule::findOrFail($id);
            $formula->update($request->only(['final_color', 'total_kg']));

            InkFormuleDetail::where('ink_formule_id', $formula->id)->delete();

            foreach ($request->components as $component) {
                InkFormuleDetail::create([
                    'ink_formule_id' => $formula->id,
                    'product_id'     => $component['product_id'],
                    'amount_kg'      => $component['kg'] ?? 0,
                    'percentage'     => $component['percentage'],
                ]);
            }
        });

        return redirect()->route('ink-formulas.index')->with('success', 'Formula updated successfully.');
    }

    /**
     * Delete a formula.
     */
    public function destroy($id)
    {
        InkFormule::findOrFail($id)->delete();
        return redirect()->route('ink-formulas.index')->with('success', 'Formula deleted successfully.');
    }
}
