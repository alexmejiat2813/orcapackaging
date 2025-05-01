<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Equipment;

class PlanningController extends Controller
{
    public function index()
{
    $machines = Equipment::getSchedulerResources(); // Lista para jqxScheduler

    return view('production.planning', compact('machines'));
}

    public function getAppointments()
{
    $records = DB::table('CommandeSchedule as cs')
        ->join('ThomasOrca.dbo.Lots as l', 'l.Lot_Id', '=', 'cs.Lot_ID')
        ->join('ThomasOrca.dbo.Commande as c', 'c.Commande_Id', '=', 'l.Commande_Id')
        ->leftJoin('ThomasOrca.dbo.Commande_Receipe as cr', 'cr.Commande_Id', '=', 'c.Commande_Id')
        ->leftJoin('ThomasOrca.dbo.Product as p', 'p.Product_ID', '=', 'l.Product_ID')
        ->where('Checked', 1)
        ->select(
                'cs.Checked',
                'cs.Schedule_ID',
                'cs.Scheduled_Date',
                'cs.Users_ID',
                'cs.Equipment_ID',
                'c.Commande_Id',
                'c.Customer_Code',
                'c.Customer_Name',
                'c.InInvoiceNumber',
                'c.Date_Commande',
                'c.Date_Demander',
                'c.Date_Expedition',
                'c.Po_Client',
                'c.Acheteur',
                'c.Transmit',
                'c.isReady_Production',
                'l.Lot_Id',
                'l.Lots_Qty',
                'l.Lots_Price',
                'l.Shipping_Qty',
                'l.Commentaire',
                'l.Lots_Complet',
                'cr.Equipment_Id as Equip_Receipe_Id',
                'cr.Value as Equip_Receipe_Value',
                'p.PrNumber',
                'p.PrDescription1'
            )
        ->get();

    /*$appointments = $records->map(function ($item) {
        return [
            'id' => $item->Schedule_ID,
            'subject' => 'Cmd ' . $item->InInvoiceNumber . ' Lot ' . $item->Lot_Id,
            'description' => $item->Commentaire  ?? 'No comments',
            'calendar' => Equipment::getDescriptionById($item->Equipment_ID),
            'start' => Carbon::parse($item->Scheduled_Date)->setTime(8, 0)->setTimezone('UTC')->toISOString(),
            'end'   => Carbon::parse($item->Scheduled_Date)->setTime(8, 0)->addHours(2)->setTimezone('UTC')->toISOString(),
        ];
    });*/

    $appointments = collect();

        foreach ($records as $item) {
            // Si no hay equipo definido, saltar
            if (is_null($item->Equip_Receipe_Id)) {
                continue;
            }

            $hora = Carbon::parse($item->Scheduled_Date)->hour;    // 14
            $minuto = Carbon::parse($item->Scheduled_Date)->minute; // 30

            // Dividir los equipos si hay más de uno (suponiendo que se puedan separar por coma)
            $equipmentIds = is_numeric($item->Equip_Receipe_Id) ? [$item->Equip_Receipe_Id] : explode(',', $item->Equip_Receipe_Id);

            foreach ($equipmentIds as $equipId) {

                $descEquip = Equipment::getDescriptionById((int) $equipId);
                $durationHours = is_numeric($item->Equip_Receipe_Value) ? (float) $item->Equip_Receipe_Value : 2; // Default a 2 horas si no hay valor

                $appointments->push([
                    'id' => $item->Schedule_ID,
            'subject' => $descEquip . ' // Cmd ' . $item->InInvoiceNumber . ' // Lot ' . $item->Lot_Id,
            'description' => $item->PrDescription1  ?? 'No comments',
            'calendar' => $descEquip,
            'start' => Carbon::parse($item->Scheduled_Date)->addHours(8)->toISOString(),
            'end'   => Carbon::parse($item->Scheduled_Date)->addHours(8+$durationHours)->toISOString(),
                ]);
            }
        }

    return response()->json($appointments);
}

public function saveAppointment(Request $request)
{
    try {
        $id = $request->input('id');
        $equipmentName  = $request->input('calendar');
        $start = Carbon::parse($request->input('start'))->setTimezone('America/Toronto');
        $end = Carbon::parse($request->input('end'))->setTimezone('America/Toronto');
        $equipmentId = Equipment::where('Equipment_Description', $equipmentName)->value('Equipment_ID');
        $now = Carbon::now()->setTimezone('America/Toronto')->format('Y-m-d H:i:s');

        if (!$equipmentId) {
            return response()->json(['error' => true, 'message' => "Equipment not found for name: $equipmentName"]);
        }

        // Validación rápida
        if (!$start || !$end) {
            return response()->json(['error' => true, 'message' => 'Invalid date format.']);
        }

        // Aquí puedes adaptar esto a tu tabla real
        $existing = DB::table('CommandeSchedule')->where('Schedule_ID', $id)->first();

        if ($existing) {
            DB::table('CommandeSchedule')
                ->where('Schedule_ID', $id)
                ->update([
                    'Scheduled_Date' => $start->format('Y-m-d H:i:s'),
                    'Equipment_ID' => $equipmentId,
                    'Updated_At' => $now,
                ]);
            return response()->json(['updated' => true]);
        } else {
            // Asignar lot ID desde algún campo si lo tienes separado en subject por ejemplo
            $lotId = intval(preg_replace('/[^0-9]/', '', $request->input('subject'))); // extraer de texto si no lo tienes separado

            DB::table('CommandeSchedule')->insert([
                'Schedule_ID' => $id,
                'Lot_ID' => $lotId,
                'Scheduled_Date' => $start->format('Y-m-d H:i:s'),
                'Created_At' => $now,
                'Equipment_ID' => $equipmentId,
                'Checked' => 1
            ]);
            return response()->json(['created' => true]);
        }

    } catch (\Throwable $e) {
        \Log::error("Error in saveAppointment: " . $e->getMessage());
        return response()->json(['error' => true, 'message' => $e->getMessage()]);
    }
}




}
?>
