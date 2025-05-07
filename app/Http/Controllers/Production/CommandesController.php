<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Production\Commande;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

    public function syncSchedule(Request $request)
{
    $lots = $request->input('lots');
    $updates = 0;
    $userId         = Auth::id(); // 👈 Make sure user is logged in

    if (!is_array($lots)) {
        return response()->json(['error' => true, 'message' => 'Invalid data format.'], 400);
    }

    foreach ($lots as $lot) {
        try {
            Log::info('Date = ' . $lot['Scheduled_Date'] );
            if (!isset($lot['lot_id']) || !isset($lot['Scheduled_Date'])) {
                continue;
            }

            $commandeId = $lot['commande_id'];
            $date = isset($lot['Scheduled_Date']) ? Carbon::parse($lot['Scheduled_Date'])->setTimezone('America/Toronto')->format('Y-m-d H:i:s') : null;

            
            
            if (!is_numeric($commandeId)) continue;
        
            
            // Buscar lotes asociados a este Commande_Id
            $lotIds = DB::table('Lots')
                ->where('Commande_Id', $commandeId)
                ->where('Lots_Cancel', 0)
                ->where('Lots_Complet', 0)
                ->pluck('Lot_Id');

            // Obtener Equipment_IDs activos relacionados al Commande
            $equipmentIds = DB::table('Commande_Receipe')
                ->where('Commande_Id', $commandeId)
                ->where('Actif', 1)
                ->whereNotNull('Equipment_Id')
                ->pluck('Equipment_Id')
                ->unique();

            Log::info('Commande_Id = ' . $commandeId );

            foreach ($lotIds as $lotId) {
                foreach ($equipmentIds as $equipmentId) {
                    $exists = DB::table('CommandeSchedule')
                        ->where('Lot_ID', $lotId)
                        ->where('Equipment_ID', $equipmentId)
                        ->exists();

                        //Log::info(' Lot = ' . $lotId . ' Equipment_ID = ' , $equipmentId . ' Date = '. $date);

                    if (!$exists) {
                        DB::table('CommandeSchedule')->insert([
                            'Lot_ID' => $lotId,
                            'Equipment_ID' => $equipmentId,
                            'Scheduled_Date' => $date,
                            'Checked' => 1,
                            'Created_At' => now(),
                            'Users_ID' => $userId
                        ]);
                        //Log::info('INSERT : ' . 'Lot_ID ' . $lotId . 'Equipment_ID' . $equipmentId . 'Scheduled_Date' . $date . 'Users_ID' . $userId);
                        $updates++;
                    } else {
                        DB::table('CommandeSchedule')
                            ->where('Lot_ID', $lotId)
                            ->where('Equipment_ID', $equipmentId)
                            ->update([
                                'Scheduled_Date' => $date,
                                'Checked' => 1,
                                'Updated_At' => now(),
                                'Users_ID' => $userId,
                            ]);
                         //Log::info('UPDATE : ' . 'Lot_ID ' . $lotId . 'Equipment_ID' . $equipmentId . 'Scheduled_Date' . $date . 'Users_ID' . $userId);
                        $updates++;
                    }
                }
            }

        } catch (\Throwable $e) {
            // Opcional: puedes registrar el error si quieres
            Log::error("Error syncing Lot_ID " . $e->getMessage());
            continue;
        }
    }

    return response()->json(['success' => true, 'updated' => $updates]);
}
}

?>
