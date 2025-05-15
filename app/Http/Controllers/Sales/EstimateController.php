<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EstimateController extends Controller
{
    /**
     * Display the sales orders view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clients = DB::table('Customer')->select('Customer_No', 'Customer_Name')->get();
        return view('sales.estimates', compact('clients')); 
    }

    public function item() {
        return view('sales.estimates_item');
    }
}

?>