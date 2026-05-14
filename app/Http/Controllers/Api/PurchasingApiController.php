<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Purchasing\PurchasingService;

class PurchasingApiController extends Controller
{
    public function __construct(private PurchasingService $service) {}

    public function openPOs()
    {
        return response()->json($this->service->getOpenPOs());
    }

    public function showPO(int $id)
    {
        return response()->json($this->service->getPOById($id));
    }

    public function posBySupplier(int $supplierId)
    {
        return response()->json($this->service->getPOsBySupplier($supplierId));
    }

    public function receivingsForPO(int $poId)
    {
        return response()->json($this->service->getReceivingsForPO($poId));
    }

    public function invoicesForSupplier(int $supplierId)
    {
        return response()->json($this->service->getInvoicesForSupplier($supplierId));
    }

    public function followUpForPO(int $poId)
    {
        return response()->json($this->service->getFollowUpForPO($poId));
    }

    public function stats()
    {
        return response()->json($this->service->stats());
    }
}
