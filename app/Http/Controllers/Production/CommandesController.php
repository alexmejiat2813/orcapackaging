<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
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

    public function syncSchedule(Request $request)
{
    $lots = $request->input('lots');
    $updates = 0;

    if (!is_array($lots)) {
        return response()->json(['error' => true, 'message' => 'Invalid data format.'], 400);
    }

    foreach ($lots as $lot) {
        try {
            if (!isset($lot['lot_id']) || !isset($lot['checked'])) {
                continue;
            }

            $lotId = $lot['lot_id'];
            $checked = $lot['checked'];
            $date = isset($lot['Scheduled_Date']) ? Carbon::parse($lot['Scheduled_Date'])->setTimezone('UTC') : null;

         
        

            // Validación lógica combinada
            if ($checked && !$date) {
                continue; // Ignorar si está marcado pero no tiene fecha
            }

            

            if (!is_numeric($lotId)) continue;

            $existing = DB::table('CommandeSchedule')->where('Lot_ID', $lotId)->first();

        

            if ($existing) {

                $needsUpdate = false;

                if ((int)$existing->Checked !== (int)$checked) {
                    $needsUpdate = true;
                }

                // Comparar fechas usando formato común
                $existingDate = $existing->Scheduled_Date ? Carbon::parse($existing->Scheduled_Date)->format('Y-m-d') : null;
                $incomingDate = $date ? Carbon::parse($date)->format('Y-m-d') : null;

                if ($existingDate !== $incomingDate) {
                    $needsUpdate = true;
                }

                        // Log para depuración
        

                if ($needsUpdate) {
                    Log::info('Validando CommandesController Insert', [
            'lot_id' => $lotId,
            'checked' => $checked,
            'Scheduled_Date' => $date
        ]);
                    DB::table('CommandeSchedule')
                        ->where('Lot_ID', $lotId)
                        ->update([
                            'Checked' => $checked,
                            'Scheduled_Date' => $date
                        ]);
                    $updates++;
                }
            } else {
               
                if ($checked == 1) { // Solo crear si el checkbox está activo
                     Log::info('Validando CommandesController Update', [
            'lot_id' => $lotId,
            'checked' => $checked,
            'Scheduled_Date' => $date
        ]);
                    DB::table('CommandeSchedule')->insert([
                        'Lot_ID' => $lotId,
                        'Scheduled_Date' => $date,
                        'Checked' => 1
                    ]);
                    $updates++;
                }
            }

            //$updates++;

        } catch (\Throwable $e) {
            // Opcional: puedes registrar el error si quieres
            \Log::error("Error syncing Lot_ID $lotId: " . $e->getMessage());
            continue;
        }
    }

    return response()->json(['success' => true, 'updated' => $updates]);
}
}

?>
