<?php

namespace App\Http\Controllers\Settings\Modules\Production\Requis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\Settings\Modules\Production\Requis\Requis;

class RequisController extends Controller
{
    // GET /settings/modules/production/requis
    public function index()
    {
        return view('settings.modules.production.requis.requis');
    }

    // GET /settings/modules/production/requis/data
    public function data()
    {
        return response()->json(Requis::with('department')->get());
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
