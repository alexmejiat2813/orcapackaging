<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Equipment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class PlanningController extends Controller
{
    /**
     * Render the planning view with available machines
     */
    public function index()
    {
        $machines = Equipment::getSchedulerResources();
        return view('production.planning', compact('machines'));
    }

    /**
     * Retrieve appointments for jqxScheduler
     */
    public function getAppointments()
    {
  
        $records = DB::table('Commande_Receipe as cr')
            ->join('ThomasOrca.dbo.Lots as l', 'cr.Commande_Id', '=', 'l.Commande_Id')
            ->join('ThomasOrca.dbo.Commande as c', 'cr.Commande_Id', '=', 'c.Commande_Id')
            ->leftJoin('ThomasOrca.dbo.Product as p', 'p.Product_ID', '=', 'l.Product_ID')
            ->leftJoin('ThomasOrca.dbo.CommandeSchedule as cs', function ($join) {
                $join->on('cs.Lot_ID', '=', 'l.Lot_Id')
                     ->on('cr.Equipment_Id', '=', 'cs.Equipment_ID');
            })
            ->whereNotNull('cr.Equipment_Id')
            ->where('p.ProductType_ID', 1)
            ->where('cr.Actif', 1)
            ->where('cs.Checked', 1)
            ->select(
                'cs.Checked',
                'cr.Commande_Id',
                'cs.Schedule_ID',
                'cs.Scheduled_Date',
                'c.InInvoiceNumber',
                'l.Lot_Id',
                'cr.Commande_Receipe_Id',
                'cr.Equipment_Id as Equip_Receipe_Id',
                'cr.Value as Equip_Receipe_Value',
                'p.PrDescription1',
                'l.Lots_Qty'
            )
            ->get();




        $appointments = collect();

        foreach ($records as $item) {
            if (is_null($item->Equip_Receipe_Id)) {
                continue;
            }

            $equipmentIds = is_numeric($item->Equip_Receipe_Id)
                ? [$item->Equip_Receipe_Id]
                : explode(',', $item->Equip_Receipe_Id);

            foreach ($equipmentIds as $equipId) {
                $equipmentDescription = Equipment::getDescriptionById((int) $equipId);
                $duration = is_numeric($item->Equip_Receipe_Value) ? (float) $item->Equip_Receipe_Value : 2;

                $appointments->push([
                    'id' => $item->Commande_Receipe_Id,
                    'description' => $item->Schedule_ID,
                    //'id' => $item->Schedule_ID,
                    'location' => $item->Lot_Id,
                    //'description' => $item->Commande_Receipe_Id,
                    'subject' => ' Cmd ' . $item->InInvoiceNumber . ' - Lot ' . $item->Lot_Id . ' : ' . $item->PrDescription1 . ' (' . $item->Lots_Qty . ')',
                    //'description' => $item->PrDescription1 ?? 'No comments',
                    'calendar' => $equipmentDescription,
                    'start' => Carbon::parse($item->Scheduled_Date)->addHours(8)->toISOString(),
                    'end' => Carbon::parse($item->Scheduled_Date)->addHours(8 + $duration)->toISOString(),
                ]);
            }
        }

        return response()->json($appointments);
    }

    /**
     * Save or update a scheduled appointment
     */
    public function saveAppointment(Request $request)
    {
        try {

            $lotId = $request->input('location');
            $scheduleId = $request->input('description');
            $receipeId = $request->input('id');
            $equipmentName = $request->input('calendar');
            $start = Carbon::parse($request->input('start'))->setTimezone('America/Toronto');
            $end = Carbon::parse($request->input('end'))->setTimezone('America/Toronto');
            $equipmentId = Equipment::where('Equipment_Description', $equipmentName)->value('Equipment_ID');
            $now = Carbon::now()->setTimezone('America/Toronto')->format('Y-m-d H:i:s');
            $duration       = $start->diffInMinutes($end) / 60; // Convert to hours
            $userId         = Auth::id(); // 👈 Make sure user is logged in

            Log::info('PlanningController - saveAppointment : Commande Receipe ID recibido:', ['receipeId' => $receipeId]);
            Log::info('PlanningController - saveAppointment : Schedule ID recibido:', ['scheduleId' => $scheduleId]);
            Log::info('PlanningController - saveAppointment : Equipment ID recibido:', ['equipment_id' => $equipmentId]);
            Log::info('PlanningController - saveAppointment : Equipment Name recibido:', ['equipmentName' => $equipmentName]);
            Log::info('PlanningController - saveAppointment : Start recibido:', ['start' => $start]);
            Log::info('PlanningController - saveAppointment : End recibido:', ['end' => $end]);
            Log::info('PlanningController - saveAppointment : Durration recibido:', ['duration' => $duration]);
            Log::info('PlanningController - saveAppointment : User ID recibido:', ['userId' => $userId]);
            Log::info('PlanningController - saveAppointment : Commande ID recibido:', ['lotId' => $lotId]);

            // Buscar Equipment_ID
            if (!$equipmentId) {
                return response()->json(['error' => true, 'message' => "PlanningController - saveAppointment : Equipment not found for name: $equipmentName"]);
            }

            // Validaciones básicas
            if (!$start || !$end || $start->gt($end)) {
                return response()->json(['error' => true, 'message' => 'PlanningController - saveAppointment : Invalid date format.']);
            }

            $commandeId = DB::table('Lots')
                ->where('Lot_Id', $lotId)
                ->value('Commande_Id');

            if (!$commandeId) {
                return response()->json(['error' => true, 'message' => 'Commande ID not found from lot.']);
            }

            // Validar si ya existen los registros en Schedule para este lot y command
            $existingSchedules = DB::table('CommandeSchedule')
                ->where('Lot_ID', $lotId)
                ->where('Equipment_ID', $equipmentId)
                ->get();

            $receipeEquipments = DB::table('Commande_Receipe')
                ->where('Commande_Id', $commandeId)
                ->where('Actif', 1)
                ->whereNotNull('Equipment_Id')
                ->pluck('Equipment_Id')
                ->unique();

            foreach ($receipeEquipments as $equipId) {
                $exists = DB::table('CommandeSchedule')
                    ->where('Lot_ID', $lotId)
                    ->where('Equipment_ID', $equipId)
                    ->exists();

                    Log::info('SELECt * FROM ThomasOrca.dbo.CommandeSchedule where Lot_ID = ' . $lotId . ' and Equipment_ID = '. $equipId);

                    Log::info('PlanningController - saveAppointment : Commande ID recibido:', ['Lot_ID' => $lotId,
                        'Scheduled_Date' => $start->format('Y-m-d H:i:s'),
                        'Created_At' => $now,
                        'Equipment_ID' => $equipId,
                        'Checked' => 1, $exists]);

                if (!$exists) {
                    
                    DB::table('CommandeSchedule')->insert([
                        //'Schedule_ID' => $scheduleId,
                        'Lot_ID' => $lotId,
                        'Scheduled_Date' => $start->format('Y-m-d H:i:s'),
                        'Created_At' => $now,
                        'Equipment_ID' => $equipId,
                        'Checked' => 1
                    ]);
                }
            }

            $previousEquipmentId = DB::table('Commande_Receipe')
                ->where('Commande_Receipe_Id', $receipeId)
                ->value('Equipment_Id');

            Log::info('PreviousEquipmentId = ' . $previousEquipmentId);

            Log::info('CommandeSchedule ', [
                ' Schedule_ID ' => $scheduleId , ' Lot_ID ' => $lotId, ' Equipment_ID' => $equipmentId,
                        'Scheduled_Date' => $start->format('Y-m-d H:i:s'),
                        'Updated_At' => $now,
                        'Users_ID' => $userId,
                        'Equipment_ID' => $equipId]);

            DB::table('CommandeSchedule')
                ->where('Schedule_ID', $scheduleId)
                ->where('Lot_ID', $lotId)
                ->where('Equipment_ID', $equipmentId)
                ->update([
                    'Scheduled_Date' => $start->format('Y-m-d H:i:s'),
                    'Equipment_ID' => $equipmentId,
                    'Users_ID' => $userId,
                    'Updated_At' => $now,
                ]);

            Log::info('Commande_Receipe ' , [
                ' Commande_Receipe_Id' => $receipeId,
                        'Equipment_Id' => $equipmentId,
                    'Value' => $duration,
                    'Modified_TimeStamps' => $now,
                    'Modified_By' => $userId,
                    'Modified_Equipment_Id' => $previousEquipmentId != $equipmentId ? $previousEquipmentId : null]);

            DB::table('Commande_Receipe')
                ->where('Commande_Receipe_Id', $receipeId)
                ->update([
                    'Equipment_Id' => $equipmentId,
                    'Value' => $duration,
                    'Modified_TimeStamps' => $now,
                    'Modified_By' => $userId,
                    'Modified_Equipment_Id' => $previousEquipmentId != $equipmentId ? $previousEquipmentId : null
                ]);

            return response()->json(['updated' => true]);

            
        } catch (\Throwable $e) {
            Log::error("PlanningController - saveAppointment : Error in saveAppointment: " . $e->getMessage());
            return response()->json(['error' => true, 'message' => $e->getMessage()]);

        }
    }
}

?>
