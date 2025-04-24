<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display the sales orders view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('sales.orders'); // Make sure this view file exists
    }
}

?>
