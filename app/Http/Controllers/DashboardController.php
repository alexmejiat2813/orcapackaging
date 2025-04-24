<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = view('dashboard.admin');

        // Return the dashboard view with no cache headers
        return Response::noCache(response($view));

        /*
        // Optional: redirect based on role (stored in session or via Auth)
        $fonctionId = Session::get('fonction_id');

        switch ($fonctionId) {
            case 1: // Administrative Assistant
                return view('dashboard.admin');

            case 6: // General Worker
                return view('dashboard.journalier');

            case 9: // Press Operator
                return view('dashboard.operateur');

            default:
                return view('dashboard');
        }
        */
    }
}


?>
