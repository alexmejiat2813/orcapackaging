<?php

namespace App\Http\Controllers\Settings\Requis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\Requis\RequisProductionStatus;

class RequisProductionStatusController extends Controller
{
    public function index() {
        return RequisProductionStatus::with(['requis','productionStatus'])->get();
    }

    public function store(Request $request) {
        return RequisProductionStatus::create($request->all());
    }

    public function update(Request $request) {
        $item = RequisProductionStatus::where('Requis_Id', $request->Requis_Id)
                ->where('Production_Status_Id', $request->Production_Status_Id)
                ->firstOrFail();
        $item->update($request->all());
        return $item;
    }

    public function destroy(Request $request) {
        return RequisProductionStatus::where('Requis_Id', $request->Requis_Id)
                ->where('Production_Status_Id', $request->Production_Status_Id)
                ->delete();
    }
}
