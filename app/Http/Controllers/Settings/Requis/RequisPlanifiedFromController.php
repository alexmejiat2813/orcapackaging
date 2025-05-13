<?php

namespace App\Http\Controllers\Settings\Requis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\Requis\RequisPlanifiedFrom;

class RequisPlanifiedFromController extends Controller
{
    public function index() {
        return RequisPlanifiedFrom::with(['requis','equipmentRegroupment'])->get();
    }

    public function store(Request $request) {
        return RequisPlanifiedFrom::create($request->all());
    }

    public function update(Request $request, $id) {
        $item = RequisPlanifiedFrom::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id) {
        return RequisPlanifiedFrom::destroy($id);
    }
}
