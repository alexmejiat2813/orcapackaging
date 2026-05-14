<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Production\RequisService;

class RequisApiController extends Controller
{
    public function __construct(private RequisService $service) {}

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show(int $id)
    {
        return response()->json($this->service->getById($id));
    }

    public function byStatus(int $statusId)
    {
        return response()->json($this->service->getByProductionStatus($statusId));
    }

    public function completionRules()
    {
        return response()->json($this->service->getCompletionRules());
    }

    public function productionFlow()
    {
        return response()->json($this->service->getProductionFlow());
    }
}
