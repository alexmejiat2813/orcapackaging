<?php

namespace App\Http\Controllers\Settings\Requis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings\Requis\RequisEstimation;

class RequisEstimationController extends Controller
{
    public function index() {
        return RequisEstimation::with(['requis'])->get();
    }

    public function store(Request $request) {
        return RequisEstimation::create($request->all());
    }

    public function update(Request $request, $id) {
        $item = RequisEstimation::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id) {
        return RequisEstimation::destroy($id);
    }
}
