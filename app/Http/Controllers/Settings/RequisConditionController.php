<?php

namespace App\Http\Controllers\Settings\Requis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\Requis\RequisProductionStatusComplete;

class RequisProductionStatusCompleteController extends Controller
{
    public function index() {
        return RequisProductionStatusComplete::with(['followType','closedType','followProductionStatus','followOperation','closedOperation'])->get();
    }

    public function store(Request $request) {
        return RequisProductionStatusComplete::create($request->all());
    }

    public function update(Request $request, $id) {
        $item = RequisProductionStatusComplete::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id) {
        return RequisProductionStatusComplete::destroy($id);
    }
}
