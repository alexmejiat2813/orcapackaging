<?php

namespace App\Http\Controllers\Settings\Modules\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\Modules\General\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Equipment::with('department')->orderBy('Equipment_Description')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Equipment_Code' => 'required|string|max:100',
            'Equipment_Description' => 'required|string|max:255',
            'Equipment_CostRate' => 'nullable|numeric',
            'Equipment_Default_Unit_Measurement_ID' => 'nullable|integer',
            'Equipment_IsActive' => 'boolean',
            'Equipment_TimeStamp' => 'nullable|date',
            'Equipment_DescEnglish' => 'nullable|string|max:255',
            'Department_ID' => 'nullable|integer',
            'Equipement_Fix_Rate' => 'nullable|numeric',
            'Equipement_Ink_Rate' => 'nullable|numeric',
            'Equipment_Variable_Rate' => 'nullable|numeric',
            'Equipment_Other_Rate1' => 'nullable|numeric',
            'Equipment_Other_Rate2' => 'nullable|numeric',
            'Equipment_isFollow' => 'boolean',
            'Equipment_Color_Prerequisites_Missing' => 'nullable|string',
            'Equipment_Shift_Allow_Overflow' => 'boolean',
            'Equipment_Shift_Overflow_Automatic' => 'boolean',
            'Location_ID' => 'nullable|integer',
            'Cost_Center_ID' => 'nullable|integer',
            'Equipment_Reservation_Visible' => 'boolean',
            'Calendar_Time_Scale' => 'nullable|integer',
        ]);

        $equipment = Equipment::create($data);
        return response()->json($equipment, 201);
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $equipment->update($request->all());
        return response()->json($equipment);
    }

    public function destroy($id)
    {
        Equipment::findOrFail($id)->delete();
        return response()->json(['message' => 'Équipement supprimé avec succès']);
    }
}
