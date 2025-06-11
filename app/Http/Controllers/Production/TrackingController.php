<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Production\Commande;

class TrackingController extends Controller
{

public function index()
    {
        return view('production.tracking');
    }

     public function getCommandes()
    {
        // Obtener los datos usando el modelo
        $commandes = Commande::where('Transmit', true)
         ->where('Commande_Transmit_First', true)
        ->where('Credit_Autorise', true)
        ->where('isReady_Production', true)
        ->where('IsCompletedLogic', false)
        ->where('IsCanceledLogic', false)
        ->orderByDesc('Commande_Id')
        ->get();

        // Retornar los resultados en formato JSON
        return response()->json($commandes);
    }

}
