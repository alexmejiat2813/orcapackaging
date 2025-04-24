<?php

namespace App\Http\Controllers\Production;

use App\Models\Production\Commande;
use App\Http\Controllers\Controller;

class CommandesController extends Controller
{

public function index()
    {
        $requests = Commande::all(); // Retrieve all supply requests
        return view('production.orders', compact('requests'));
    }

    public function getCommandes()
    {
        // Obtener los datos usando el modelo
        $commandes = Commande::getCommandeData();

        // Retornar los resultados en formato JSON
        return response()->json($commandes);
    }
}

?>
