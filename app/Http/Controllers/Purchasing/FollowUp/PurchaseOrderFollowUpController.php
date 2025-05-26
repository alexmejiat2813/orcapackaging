<?php

namespace App\Http\Controllers\Purchasing\FollowUp;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Models\Purchasing\POFollowUpLog;
use App\Models\Purchasing\PurchaseOrderFollowUp;

use App\Models\Settings\Modules\General\POFollowUpStatus;


class PurchaseOrderFollowUpController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = PurchaseOrderFollowUp::query();

            if ($request->has('supplier_id')) {
                $query->where('Supplier_ID', $request->supplier_id);
            }

            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('PO_Date', [$request->from_date, $request->to_date]);
            }

            $records = $query->orderByDesc('PO_Date')->orderByDesc('PO_ID')->get();

            return response()->json([
                'success' => true,
                'data' => $records
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving filtered data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logs(Request $request)
    {
        $poId = $request->get('po_id');

        $logs = POFollowUpLog::with(['user', 'status'])
            ->where('PO_ID', $poId)
            ->get()
            ->map(function ($log) {
                return [
                    'PO_FollowUp_ID' => $log->PO_FollowUp_ID,
                    'Followup_Date' => $log->Followup_Date,
                    'Followup_By' => $log->Followup_By,
                    'Followup_Notes' => $log->Followup_Notes,
                    'Status_ID' => $log->Status_ID,
                    'User_Name' => $log->user->Users_Name ?? '',
                    'Status_Name' => $log->status->Status_Name ?? ''
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    public function getStatuses(): JsonResponse
{
    $statuses = POFollowUpStatus::select('Status_ID', 'Status_Name')->get();

    return response()->json([
        'success' => true,
        'data' => $statuses
    ]);
}



public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'po_id' => 'required|integer',
            'status_id' => 'required|integer',
            'follow_up_notes' => 'nullable|string',
        ]);

        $log = new POFollowUpLog();
        $log->PO_ID = $validated['po_id'];
        $log->Status_ID = $validated['status_id'];
        $log->Followup_Notes = $validated['follow_up_notes'];
        $log->Followup_Date = now();
        $log->Followup_By = Auth::id() ?? 0; // Si decides usar login más adelante

        $log->save();

        return response()->json(['success' => true, 'message' => 'Follow-up saved successfully.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error saving follow-up.', 'error' => $e->getMessage()], 500);
    }
}



}





