<?php

namespace App\Services\Purchasing;

use App\Models\Purchasing\PO;
use App\Models\Purchasing\PODetail;
use App\Models\Purchasing\Receiving;
use App\Models\Purchasing\ReceivingDetail;
use App\Models\Purchasing\SupplierInvoice;
use App\Models\Purchasing\PurchaseOrderFollowUp;
use Illuminate\Support\Facades\DB;

class PurchasingService
{
    public function getOpenPOs()
    {
        return PO::with(['supplier', 'details'])
            ->where('PO_Completed', 0)
            ->where('PO_Cancel', 0)
            ->orderByDesc('PO_ID')
            ->get();
    }

    public function getPOById(int $id): PO
    {
        return PO::with(['supplier', 'details'])
            ->findOrFail($id);
    }

    public function getPOsBySupplier(int $supplierId)
    {
        return PO::with(['details'])
            ->where('Supplier_ID', $supplierId)
            ->orderByDesc('PO_ID')
            ->get();
    }

    public function getReceivingsForPO(int $poId)
    {
        return Receiving::with(['details'])
            ->where('PO_ID', $poId)
            ->orderByDesc('Receiving_ID')
            ->get();
    }

    public function getInvoicesForSupplier(int $supplierId)
    {
        return SupplierInvoice::with(['details'])
            ->where('Supplier_ID', $supplierId)
            ->orderByDesc('Supplier_Invoice_Id')
            ->get();
    }

    public function getFollowUpForPO(int $poId)
    {
        return PurchaseOrderFollowUp::where('PO_ID', $poId)
            ->orderByDesc('PO_FollowUp_Date')
            ->get();
    }

    public function stats(): array
    {
        $pos = DB::connection('sqlsrv')->table('ThomasOrca.dbo.PO');
        $inv = DB::connection('sqlsrv')->table('ThomasOrca.dbo.Supplier_Invoice');

        return [
            'open_pos'         => (clone $pos)->where('PO_Completed', 0)->where('PO_Cancel', 0)->count(),
            'total_pos'        => (clone $pos)->count(),
            'pending_invoices' => (clone $inv)->where('Invoice_Paid', 0)->count(),
        ];
    }
}
