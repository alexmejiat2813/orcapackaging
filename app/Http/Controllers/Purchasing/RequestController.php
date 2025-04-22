<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Purchasing\Request;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Request::all(); // consulta toda la tabla
        return view('purchasing.requests', compact('requests'));
    }

    public function list()
    {
        $requests = Request::all(); // consulta toda la tabla
        return response()->json($requests);
    }


}

?>
