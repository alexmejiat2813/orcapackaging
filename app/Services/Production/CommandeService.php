<?php

namespace App\Services\Production;

use App\Models\Production\Commande;
use App\Models\Production\CommandeReceipe;
use App\Models\Production\CommandeSchedule;
use Illuminate\Support\Facades\DB;

class CommandeService
{
    public function getActive()
    {
        return Commande::with(['customer', 'productionStatus'])
            ->where('Complet', 0)
            ->where('Cancel', 0)
            ->orderByDesc('Commande_Id')
            ->get();
    }

    public function getReadyForProduction()
    {
        return Commande::with(['customer', 'productionStatus'])
            ->where('Complet', 0)
            ->where('Cancel', 0)
            ->where('isReady_Production', 1)
            ->orderByDesc('Commande_Id')
            ->get();
    }

    public function getById(int $id)
    {
        return Commande::with(['customer', 'productionStatus'])
            ->findOrFail($id);
    }

    public function getByCustomer(int $customerId)
    {
        return Commande::with(['customer', 'productionStatus'])
            ->where('Customer_Id', $customerId)
            ->where('Complet', 0)
            ->where('Cancel', 0)
            ->orderByDesc('Commande_Id')
            ->get();
    }

    public function getByStatus(int $statusId)
    {
        return Commande::with(['customer', 'productionStatus'])
            ->where('Production_Status_Id', $statusId)
            ->where('Cancel', 0)
            ->orderByDesc('Commande_Id')
            ->get();
    }

    public function getWithScheduleForEquipment(int $equipmentId, ?string $date = null)
    {
        $query = DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.View_Commandes')
            ->leftJoin('ThomasOrca.dbo.CommandeSchedule', 'View_Commandes.lot_id', '=', 'CommandeSchedule.Lot_ID')
            ->where('CommandeSchedule.Equipment_ID', $equipmentId);

        if ($date) {
            $query->whereRaw('CAST(CommandeSchedule.Scheduled_Date AS DATE) = ?', [$date]);
        } else {
            $query->whereRaw('CAST(CommandeSchedule.Scheduled_Date AS DATE) = CAST(GETDATE() AS DATE)');
        }

        return $query->get();
    }

    public function stats(): array
    {
        $base = DB::connection('sqlsrv')->table('ThomasOrca.dbo.Commande');

        return [
            'total_active'    => (clone $base)->where('Complet', 0)->where('Cancel', 0)->count(),
            'ready_prod'      => (clone $base)->where('Complet', 0)->where('Cancel', 0)->where('isReady_Production', 1)->count(),
            'completed'       => (clone $base)->where('Complet', 1)->count(),
            'cancelled'       => (clone $base)->where('Cancel', 1)->count(),
        ];
    }
}
