<?php

namespace App\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Supplier\Supplier;

class SupplierController extends Controller
{
    // Obtener todos los proveedores
    public function index()
    {
        return response()->json(Supplier::allSuppliers());
    }

    // Obtener un proveedor por código
    public function show($supplierNo)
    {
        $supplier = Supplier::findByNo($supplierNo);

        if (!$supplier) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        return response()->json([
            'supplier_id' => $supplier->Supplier_No,
            'supplier_name' => $supplier->Supplier_Name,
            'contact_person' => $supplier->Supplier_CareOf,
            'address1' => $supplier->Supplier_Address,
            'address2' => $supplier->Supplier_Address2,
            'city' => $supplier->Supplier_City,
            'postal_code' => $supplier->Supplier_PostalCode,
            'phone' => $supplier->Supplier_PhoneNumber1,
            'fax' => $supplier->Supplier_PhoneNumber2
        ]);
    }

    // Crear un nuevo proveedor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Supplier_No' => 'required|string|unique:Supplier,Supplier_No',
            'Supplier_Name' => 'required|string',
            // Agrega validaciones para los demás campos si es necesario
        ]);

        $supplier = Supplier::createSupplier($validated);

        return response()->json($supplier, 201);
    }

    // Actualizar proveedor por ID
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        $supplier->updateSupplier($request->all());

        return response()->json($supplier);
    }

    // Eliminar proveedor
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        $supplier->deleteSupplier();

        return response()->json(['message' => 'Proveedor eliminado']);
    }

    // Obtener contactos del proveedor
    public function getContacts($supplierNo)
    {
        $supplier = Supplier::findByNo($supplierNo);

        if (!$supplier) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        $contacts = $supplier->activeContacts()->get(['Asset_ID', 'Asset_FName', 'Asset_Name']);

        return response()->json(['contacts' => $contacts]);
    }
}

