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
use App\Services\Production\CommandeService;
use App\Models\OrderNote;

class CommandesController extends Controller
{

public function index()
    {
        $requests = Commande::all(); // Retrieve all supply requests
        return view('production.orders', compact('requests'));
    }

    public function getTodayScheduledCommandes(Request $request)
{
    $equipmentId = $request->input('equipment_id', 17); // default to 17 if not provided

    $results = DB::table('ThomasOrca.dbo.View_Commandes')
        ->leftJoin('ThomasOrca.dbo.CommandeSchedule', 'View_Commandes.lot_id', '=', 'CommandeSchedule.Lot_ID')
        ->where('CommandeSchedule.Equipment_ID', $equipmentId)
        ->whereRaw('CAST(CommandeSchedule.Scheduled_Date AS DATE) = CAST(GETDATE() AS DATE)')
        ->get();

    return response()->json($results);
}

    /**
     * Delete a schedule and its associated receipe line.
     */
    public function deleteScheduleWithReceipe(Request $request)
    {
        $scheduleId = $request->input('Schedule_Id');
        $commandeReceipeId = $request->input('Commande_Receipe_Id');

        Log::info('scheduleId = ' . $scheduleId . ' commandeReceipeId = ' . $commandeReceipeId);

        if (!$scheduleId || !$commandeReceipeId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing Schedule_Id or Commande_Receipe_Id'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // 1. Delete from CommandeSchedule
            DB::table('ThomasOrca.dbo.CommandeSchedule')
                ->where('Schedule_Id', $scheduleId)
                ->delete();

            // 2. Delete from Commande_Receipe
            DB::table('ThomasOrca.dbo.Commande_Receipe')
                ->where('Commande_Receipe_Id', $commandeReceipeId)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Schedule and associated receipe deleted successfully."
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Delete failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting schedule.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function getCommandesWithSchedule()
{
    return DB::table('ThomasOrca.dbo.Commande_Receipe')
    ->leftJoin('ThomasOrca.dbo.Commande', 'Commande.Commande_Id', '=', 'Commande_Receipe.Commande_Id')
    ->leftJoin('ThomasOrca.dbo.Customer', 'Customer.Customer_ID', '=', 'Commande.Customer_Id')
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
        'CommandeSchedule.Schedule_Id',
        'Commande_Receipe.Commande_Receipe_Id',
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
    elseif (isset($payload['lot_id'], $payload['commande_id'], $payload['Scheduled_Date'], $payload['Equipment_Id'])) {
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
            Log::info('lot_id = ' . $lot['lot_id'] . ' commande_id = ' . $lot['commande_id']  . ' Scheduled_Date = ' . $lot['Scheduled_Date'] . ' Equipment_Id = ' . $lot['Equipment_Id']);
            if (!isset($lot['lot_id']) || !isset($lot['commande_id']) || !isset($lot['Scheduled_Date']) || !isset($lot['Equipment_Id'])) {
                continue; // skip invalid rows
            }

            Log::info('lot_id = ' . $lot['lot_id'] . ' commande_id = ' . $lot['commande_id']  . ' Scheduled_Date = ' . $lot['Scheduled_Date'] . ' Equipment_Id = ' . $lot['Equipment_Id']);

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
                ->where('Equipment_Id', $lot['Equipment_Id'])
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

public function show(int $id)
{
    $commande = (new CommandeService())->getById($id);

    $lots = DB::connection('sqlsrv')
        ->table('ThomasOrca.dbo.Lots as l')
        ->leftJoin('ThomasOrca.dbo.Product as p', 'p.Product_ID', '=', 'l.Product_Id')
        ->where('l.Commande_Id', $id)
        ->select('l.Lot_Id', 'l.Lots_Qty', 'l.Lots_Complet', 'l.Lots_Cancel', 'p.PrNumber', 'p.PrDescription1')
        ->get();

    $recipe = DB::connection('sqlsrv')
        ->table('ThomasOrca.dbo.Commande_Receipe as cr')
        ->leftJoin('ThomasOrca.dbo.Equipment as e', 'e.Equipment_ID', '=', 'cr.Equipment_Id')
        ->where('cr.Commande_Id', $id)
        ->where('cr.Actif', 1)
        ->select('cr.Commande_Receipe_Id', 'cr.Equipment_Id', 'e.Equipment_Description', 'cr.Value as Hours')
        ->get();

    $notes = OrderNote::with('user')
        ->where('notable_type', 'commande')
        ->where('notable_id', $id)
        ->orderByDesc('created_at')
        ->get();

    return view('production.show', compact('commande', 'lots', 'recipe', 'notes'));
}

public function showModalImage(Request $request) {

    $productNb = $request->input('productNb');

    $imagePath = DB::table('Product')
        ->where('PrNumber', $productNb)
        ->value('PrPath'); 


    //if (!file_exists($imagePath)) {
    //    return response()->json([
    //        'success' => false,
    //        'message' => "Aucune image pour ce produit"
    //    ]);
    //}

    $imagePath =   str_replace('\\\\192.168.0.97\\storage\\app\\public\\', 'storage\\', $imagePath);

    return response()->json([
        'success' => true,
        'image_path' => $imagePath
    ]);
}

}

?>
