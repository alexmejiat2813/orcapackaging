<?php

namespace App\Http\Controllers;

use App\Models\Inventory\InkFormule;
use App\Models\Inventory\InkFormuleComponent;
use App\Models\Product;
use Illuminate\Http\Request;

class InkFormuleComponentController extends Controller
{
    /**
     * Muestra los componentes de una fórmula específica.
     */
    public function index($formulaId)
    {
        $formula = InkFormule::findOrFail($formulaId);
        $components = $formula->components()->with('product')->get();

        return view('ink_components.index', compact('formula', 'components'));
    }

    /**
     * Muestra el formulario para agregar un nuevo componente.
     */
    public function create($formulaId)
    {
        $formula = InkFormule::findOrFail($formulaId);
        $products = Product::all();

        return view('ink_components.create', compact('formula', 'products'));
    }

    /**
     * Guarda un nuevo componente en la fórmula.
     */
    public function store(Request $request, $formulaId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        InkFormuleComponent::create([
            'ink_formula_id' => $formulaId,
            'product_id' => $request->product_id,
            'percentage' => $request->percentage,
        ]);

        return redirect()->route('ink-components.index', $formulaId)
                         ->with('success', 'Component added successfully.');
    }

    /**
     * Muestra el formulario para editar un componente.
     */
    public function edit($formulaId, $componentId)
    {
        $component = InkFormuleComponent::where('ink_formula_id', $formulaId)
                                        ->findOrFail($componentId);

        $products = Product::all();
        return view('ink_components.edit', compact('component', 'products'));
    }

    /**
     * Actualiza un componente de la fórmula.
     */
    public function update(Request $request, $formulaId, $componentId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $component = InkFormuleComponent::where('ink_formula_id', $formulaId)
                                        ->findOrFail($componentId);

        $component->update([
            'product_id' => $request->product_id,
            'percentage' => $request->percentage,
        ]);

        return redirect()->route('ink-components.index', $formulaId)
                         ->with('success', 'Component updated successfully.');
    }

    /**
     * Elimina un componente.
     */
    public function destroy($formulaId, $componentId)
    {
        $component = InkFormuleComponent::where('ink_formula_id', $formulaId)
                                        ->findOrFail($componentId);

        $component->delete();

        return redirect()->route('ink-components.index', $formulaId)
                         ->with('success', 'Component deleted successfully.');
    }
}
