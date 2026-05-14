<?php

namespace App\Services\Production;

use App\Models\Settings\Modules\Production\Requis\Requis;
use App\Models\Settings\Modules\Production\Requis\RequisCondition;
use App\Models\Settings\Modules\Production\Requis\RequisEstimation;
use App\Models\Settings\Modules\Production\Requis\RequisProductionStatus;
use App\Models\Settings\Modules\Production\Requis\RequisProductionStatusComplete;
use Illuminate\Support\Facades\DB;

class RequisService
{
    public function getAll()
    {
        return Requis::with(['conditions', 'estimations', 'productionStatuses'])
            ->orderBy('Requis_Id')
            ->get();
    }

    public function getById(int $id)
    {
        return Requis::with(['conditions', 'estimations', 'productionStatuses'])
            ->findOrFail($id);
    }

    public function getByProductionStatus(int $statusId)
    {
        return RequisProductionStatus::with(['requis'])
            ->where('Production_Status_Id', $statusId)
            ->get()
            ->pluck('requis')
            ->filter();
    }

    public function getConditionsForRequisAndStatus(int $requisId, int $statusId): bool
    {
        return RequisCondition::where('Requis_Id', $requisId)
            ->where('Production_Status_Id', $statusId)
            ->exists();
    }

    public function getCompletionRules()
    {
        return RequisProductionStatusComplete::with([
            'followType',
            'closedType',
            'followProductionStatus',
            'followOperation',
            'closedOperation',
        ])->get();
    }

    public function getEstimationsForRequisByLot(int $requisId)
    {
        return RequisEstimation::where('Requis_Id', $requisId)
            ->get();
    }

    public function getProductionFlow()
    {
        return DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.Requis_Production_Status as rps')
            ->join('ThomasOrca.dbo.Requis as r', 'r.Requis_Id', '=', 'rps.Requis_Id')
            ->join('ThomasOrca.dbo.Production_Status as ps', 'ps.Production_Status_Id', '=', 'rps.Production_Status_Id')
            ->select(
                'r.Requis_Id',
                'r.Requis_Code',
                'r.Requis_Description',
                'ps.Production_Status_Id'
            )
            ->orderBy('ps.Production_Status_Id')
            ->get();
    }
}
