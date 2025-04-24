<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function index()
    {
     
         $view = view('dashboard.admin');

    return Response::noCache(response($view));

    /*$fonctionId = Session::get('fonction_id');

        switch ($fonctionId) {
            case 1: // Adjoin administratif
                return view('dashboard.admin');

            case 6: // Journalier
                return view('dashboard.journalier');

            case 9: // Opérateur de presse
                return view('dashboard.operateur');

            default:
                return view('dashboard');
        }*/
    }
}

?>
