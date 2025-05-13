<?php

namespace App\Http\Controllers\Settings\Requis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\Requis\Requis;
use Illuminate\Support\Facades\Validator;

class RequisController extends Controller
{
    /**
     * List all Requis records
     */
    public function index()
    {
        $requis = Requis::with(['department'])->get();
        return response()->json($requis);
    }

    /**
     * Store a new Requis
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Requis_Code' => 'required|string|unique:Requis,Requis_Code',
            'Requis_Description' => 'required|string',
            'Requis_Description_English' => 'nullable|string',
            'Requis_Department_Id' => 'required|exists:Department,Department_ID',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $requis = Requis::create($request->all());
        return response()->json($requis, 201);
    }

    /**
     * Update an existing Requis
     */
    public function update(Request $request, $id)
    {
        $requis = Requis::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'Requis_Code' => 'sometimes|required|string|unique:Requis,Requis_Code,' . $id . ',Requis_Id',
            'Requis_Description' => 'sometimes|required|string',
            'Requis_Department_Id' => 'sometimes|required|exists:Department,Department_ID',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $requis->update($request->all());
        return response()->json($requis);
    }

    /**
     * Delete a Requis record
     */
    public function destroy($id)
    {
        $requis = Requis::findOrFail($id);
        $requis->delete();

        return response()->json(['message' => 'Requis deleted successfully.']);
    }
}
