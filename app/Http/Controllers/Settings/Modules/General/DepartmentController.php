<?php

namespace App\Http\Controllers\Settings\Modules\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\Modules\General\Department;

class DepartmentController extends Controller
{

    // GET /settings/modules/production/requis
    public function index()
    {
        return view('settings.modules.general.department');
    }

    // GET /settings/modules/production/requis/data
    public function data()
    {
         return response()->json(Department::orderBy('Department_Description')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Department_Description' => 'required|string|max:255',
            'Department_DesEnglish' => 'nullable|string|max:255',
            'Periode_Id' => 'nullable|integer',
            'ID_GraphICC' => 'nullable|integer',
        ]);

        $department = Department::create($data);
        return response()->json($department, 201);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $data = $request->validate([
            'Department_Description' => 'required|string|max:255',
            'Department_DesEnglish' => 'nullable|string|max:255',
            'Periode_Id' => 'nullable|integer',
            'ID_GraphICC' => 'nullable|integer',
        ]);

        $department->update($data);
        return response()->json($department);
    }

    public function destroy($id)
    {
        Department::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
