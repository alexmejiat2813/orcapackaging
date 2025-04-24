<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        return view('sales.orders'); // asegúrate que esta vista exista
    }
}

?>
