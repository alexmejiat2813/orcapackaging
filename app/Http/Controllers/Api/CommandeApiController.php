<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Production\CommandeService;
use Illuminate\Http\Request;

class CommandeApiController extends Controller
{
    public function __construct(private CommandeService $service) {}

    public function index()
    {
        return response()->json($this->service->getActive());
    }

    public function readyForProduction()
    {
        return response()->json($this->service->getReadyForProduction());
    }

    public function show(int $id)
    {
        return response()->json($this->service->getById($id));
    }

    public function byCustomer(int $customerId)
    {
        return response()->json($this->service->getByCustomer($customerId));
    }

    public function byStatus(int $statusId)
    {
        return response()->json($this->service->getByStatus($statusId));
    }

    public function todaySchedule(Request $request)
    {
        $equipmentId = (int) $request->input('equipment_id', 17);
        $date = $request->input('date');
        return response()->json($this->service->getWithScheduleForEquipment($equipmentId, $date));
    }

    public function stats()
    {
        return response()->json($this->service->stats());
    }
}
