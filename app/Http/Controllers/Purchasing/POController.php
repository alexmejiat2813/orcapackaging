<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Purchasing\PO;
use App\Models\Purchasing\PODetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Purchasing\PurchasingService;
use App\Models\OrderNote;

class POController extends Controller
{
    public function index(Request $request)
    {
        $data = PO::with([
            'supplier:Supplier_ID,Supplier_Name,Supplier_No',
            'receptions'
        ])
        ->orderByDesc('PO_ID')
        ->get();

        $formatted = [];

        foreach ($data as $po) {
            $hasReception = $po->receptions->isNotEmpty();
            $allCompleted = $po->PO_Completed;

            $status = '';
            if ($hasReception && $allCompleted) {
                $status = 'Complété';
            } elseif ($hasReception && !$allCompleted) {
                $status = 'Partiel';
            }

            $formatted[] = [
                'PO_ID'         => $po->PO_ID,
                'PO_No'         => $po->PO_No,
                'PO_Date'       => $po->PO_Date,
                'PO_Date_Reception'       => $po->PO_Date_Reception,
                'PO_Note'       => $po->PO_Note,
                'PO_Total'       => $po->PO_Total,
                'Supplier_Name' => $po->supplier->Supplier_Name ?? '',
                'Supplier_No'   => $po->supplier->Supplier_No ?? '',
                'PO_Completed'  => $po->PO_Completed,
                'PO_Transmit'   => $po->PO_Transmit,
                'PO_Cancel'     => $po->PO_Cancel,
                'PO_Lock'       => $po->PO_Lock,
                'Reception_Status' => $status,
            ];
        }

        return response()->json($formatted);
    }


    public function show(int $id)
    {
        $po = (new PurchasingService())->getPOById($id);

        $details = DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.PO_Detail as pd')
            ->leftJoin('ThomasOrca.dbo.Product as p', 'p.Product_ID', '=', 'pd.Product_ID')
            ->where('pd.PO_ID', $id)
            ->select(
                'pd.PO_Detail_ID', 'pd.Product_ID', 'pd.Quantity', 'pd.Unit_Price',
                'pd.Unit_Qty', 'pd.PO_Detail_Note',
                'p.PrNumber', 'p.PrDescription1'
            )
            ->get();

        $receivings = (new PurchasingService())->getReceivingsForPO($id);

        $notes = OrderNote::with('user')
            ->where('notable_type', 'po')
            ->where('notable_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('purchasing.show', compact('po', 'details', 'receivings', 'notes'));
    }

    public function data()
    {
        $poList = PO::with([
            'supplier:id,Supplier_Name,Supplier_No',
            'details.product:id,PrNumber,PrDescription1',
            'receptions'
        ])
        ->orderByDesc('PO_ID')
        ->take(200) // Limit to latest 200 for performance
        ->get();

        // Flatten the structure for jqxGrid
        $formatted = [];

        foreach ($poList as $po) {
            foreach ($po->details as $detail) {
                $formatted[] = [
                    'PO_ID' => $po->PO_ID,
                    'PO_No' => $po->PO_No,
                    'PO_Date' => $po->PO_Date,
                    'PO_Note' => $po->PO_Note,
                    'Supplier_Name' => $po->supplier->Supplier_Name ?? '',
                    'Supplier_No' => $po->supplier->Supplier_No ?? '',
                    'Product_Supplier_Code' => $detail->product->Supplier_Code ?? '',
                    'Product_ID' => $detail->Product_ID,
                    'PrNumber' => $detail->product->PrNumber ?? '',
                    'PrDescription1' => $detail->product->PrDescription1 ?? '',
                    'Order_Quantity' => $detail->Quantity ?? 0,
                    'Order_Price' => $detail->Unit_Price ?? 0,
                    'Unit_Qty' => $detail->Unit_Qty ?? '',
                    'Unit_Price' => $detail->Unit_Price ?? '',
                ];
            }
        }

        return response()->json($formatted);
    }
}
