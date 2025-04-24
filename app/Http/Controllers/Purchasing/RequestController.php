<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Purchasing\Request;

class RequestController extends Controller
{
    /**
     * Display all supply requests in the view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = Request::all(); // Retrieve all supply requests
        return view('purchasing.requests', compact('requests'));
    }

    /**
     * Return all supply requests as JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $requests = Request::all(); // Retrieve all supply requests
        return response()->json($requests);
    }
}
?>
