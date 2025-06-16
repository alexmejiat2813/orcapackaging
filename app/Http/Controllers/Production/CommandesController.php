<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Production\Commande;
use App\Models\Production\CommandeSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommandesController extends Controller
{

public function index()
    {
        $requests = Commande::all(); // Retrieve all supply requests
        return view('production.orders', compact('requests'));
    }

    public static function getCommandesWithSchedule()
{
    return DB::table('ThomasOrca.dbo.Commande')
        ->leftJoin('ThomasOrca.dbo.Customer', 'Customer.Customer_ID', '=', 'Commande.Customer_Id')
        ->leftJoin('ThomasOrca.dbo.Commande_Receipe', 'Commande.Commande_Id', '=', 'Commande_Receipe.Commande_Id')
        ->leftJoin('ThomasOrca.dbo.Lots', 'Commande.Commande_Id', '=', 'Lots.Commande_Id')
        ->leftJoin('ThomasOrca.dbo.Product', 'Product.Product_ID', '=', 'Lots.Product_Id')
        ->leftJoin('ThomasOrca.dbo.Equipment', 'Equipment.Equipment_ID', '=', 'Commande_Receipe.Equipment_Id')
        ->leftJoin('ThomasOrca.dbo.CommandeSchedule', function ($join) {
            $join->on('Commande_Receipe.Equipment_Id', '=', 'CommandeSchedule.Equipment_ID')
                 ->on('Lots.Lot_Id', '=', 'CommandeSchedule.Lot_ID');
        })
        ->select(
            'Commande.Commande_Id',
            'Commande.InInvoiceNumber',
            'Customer.Customer_No',
            'Customer.Customer_Name',
            'Lots.Lot_Id',
            'Product.PrNumber',
            'Product.PrDescription1',
            'Commande_Receipe.Equipment_Id',
            'Equipment.Equipment_Description',
            'Commande_Receipe.Value as qtyHeures',
            'CommandeSchedule.Scheduled_Date'
        )
        ->where('Commande.Complet', 0)
        ->where('Commande.Cancel', 0)
        ->where('Commande.isReady_Production', 1)
        ->where('Lots.Lots_Complet', 0)
        ->where('Lots.Lots_Cancel', 0)
        ->where('Product.ProductType_ID', 1)
        ->whereNotNull('Commande_Receipe.Equipment_Id')
        ->orderBy('Lots.Lot_Id')
        ->orderBy('Commande_Receipe.Equipment_Id')
        ->get();
}


    public function getCommandes()
    {
        // Obtener los datos usando el modelo
        $commandes = Commande::orderByDesc('Commande_Id')->get();

        // Retornar los resultados en formato JSON
        return response()->json($commandes);
    }

    public function syncSchedule_old(Request $request)
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
            $date = isset($lot['Scheduled_Date']) ? Carbon::parse($lot['Scheduled_Date'])->addHours(11)->setTimezone('America/Toronto')->format('Y-m-d H:i:s') : null;

            
            
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

public function syncSchedule(Request $request)
{
    $updates = 0;
    $userId = Auth::id();

    // Extraer todos los datos del request JSON
    $payload = $request->json()->all();

    // Caso A: viene como array de lots
    if (isset($payload['lots']) && is_array($payload['lots'])) {
        $lots = $payload['lots'];
    }
    // Caso B: viene un solo objeto plano con los campos necesarios
    elseif (isset($payload['lot_id'], $payload['commande_id'], $payload['Scheduled_Date'])) {
        $lots = [$payload];
    }
    // Caso inválido
    else {
        return response()->json([
            'success' => false,
            'message' => 'Invalid payload received.',
            'payload_received' => $payload
        ], 400);
    }

    foreach ($lots as $lot) {
        try {
            Log::info('lot_id = ' . $lot['lot_id'] . ' commande_id = ' . $lot['commande_id']  . ' Scheduled_Date = ' . $lot['Scheduled_Date']);
            if (!isset($lot['lot_id']) || !isset($lot['commande_id']) || !isset($lot['Scheduled_Date'])) {
                continue; // skip invalid rows
            }

            Log::info('lot_id = ' . $lot['lot_id'] . ' commande_id = ' . $lot['commande_id']  . ' Scheduled_Date = ' . $lot['Scheduled_Date']);

            $commandeId = $lot['commande_id'];
            $date = Carbon::parse($lot['Scheduled_Date'])
                ->setTimezone('America/Toronto')
                ->format('Y-m-d H:i:s');

            if (!is_numeric($commandeId)) continue;

            // Get all lots for the given Commande_Id
            $lotIds = DB::table('Lots')
                ->where('Commande_Id', $commandeId)
                ->where('Lots_Cancel', 0)
                ->where('Lots_Complet', 0)
                ->pluck('Lot_Id');

            // Get active Equipment_IDs associated with the Commande
            $equipmentIds = DB::table('Commande_Receipe')
                ->where('Commande_Id', $commandeId)
                ->where('Actif', 1)
                ->whereNotNull('Equipment_Id')
                ->pluck('Equipment_Id')
                ->unique();

            foreach ($lotIds as $lotId) {
                foreach ($equipmentIds as $equipmentId) {
                    $exists = DB::table('CommandeSchedule')
                        ->where('Lot_ID', $lotId)
                        ->where('Equipment_ID', $equipmentId)
                        ->exists();

                    if (!$exists) {
                        DB::table('CommandeSchedule')->insert([
                            'Lot_ID' => $lotId,
                            'Equipment_ID' => $equipmentId,
                            'Scheduled_Date' => $date,
                            'Checked' => 1,
                            'Created_At' => now(),
                            'Users_ID' => $userId
                        ]);
                        $updates++;
                    } else {
                        DB::table('CommandeSchedule')
                            ->where('Lot_ID', $lotId)
                            ->where('Equipment_ID', $equipmentId)
                            ->update([
                                'Scheduled_Date' => $date,
                                'Checked' => 1,
                                'Updated_At' => now(),
                                'Users_ID' => $userId
                            ]);
                        $updates++;
                    }
                }
            }

        } catch (\Throwable $e) {
            Log::error("Error syncing schedule: " . $e->getMessage());
            continue;
        }
    }

    return response()->json(['success' => true, 'updated' => $updates]);
}


public function getSchedulesWithEquipment()
{
    $data = CommandeSchedule::getScheduleWithReceipe();

    return response()->json($data);
}

}

?>
