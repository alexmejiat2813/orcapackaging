<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Purchasing\JotformSuppliesRequest;
use App\Services\Jotform\JotformService;
use App\Services\Jotform\JotformValidationsService;

class JotformSuppliesController extends Controller
{
    public function importAllSubmissions()
    {
        try {
            $service = new JotformService();
            $formId = '250704767667064';
            $submissions = $service->fetchSubmissions($formId);

            $inserted = 0;
            $updated = 0;

            foreach ($submissions as $submission) {
                if (!JotformValidationsService::validate($submission)) {
                    Log::warning("❌ Invalid submission data", $submission);
                    continue;
                }

                $existing = JotformSuppliesRequest::where('jotform_id', $submission['submission_id'])->first();

                if ($existing) {
                    if (JotformSuppliesRequest::isModified($existing, $submission)) {
                        JotformSuppliesRequest::insertOrUpdateFromSubmission($submission);
                        $updated++;
                    }
                } else {
                    JotformSuppliesRequest::insertOrUpdateFromSubmission($submission);
                    $inserted++;
                }
            }

            return response()->json([
                'success' => true,
                'inserted' => $inserted,
                'updated' => $updated,
                'message' => "✅ $inserted inserted, $updated updated"
            ]);
        } catch (\Throwable $e) {
			Log::error("❌ Exception in importAllSubmissions: " . $e->getMessage(), [
				'trace' => $e->getTraceAsString()
			]);
			return response()->json([
				'success' => false,
				'error' => 'Server error'
			], 500);
		}
	}

    public function receiveWebhook(Request $request)
    {
        $payload = $request->all();

        if (!$payload || !isset($payload['submissionID'])) {
            return response("⛔️ Invalid Payload", 400);
        }

        try {
            $submissionId = $payload['submissionID'];
            $service = new JotformService();

            $submission = $service->transformSingleSubmission($payload);

            if (!JotformValidationsService::validate($submission)) {
                Log::warning("❌ Invalid webhook submission", $submission);
                return response("⛔️ Invalid data", 400);
            }

            $existing = JotformSuppliesRequest::where('jotform_id', $submissionId)->exists();

            if (!$existing) {
                JotformSuppliesRequest::insertOrUpdateFromSubmission($submission);
                Log::info("✅ Webhook inserted: ID $submissionId");
            } else {
                Log::info("⚠️ Webhook submission already exists: ID $submissionId");
            }

            return response("✅ Submission handled", 200);
        } catch (\Exception $e) {
            Log::error("❌ Webhook error: " . $e->getMessage());
            return response("❌ Server error", 500);
        }
    }

public function updateManaged(Request $request)
{
    // ✅ Validación de entrada usando Laravel
    $validated = $request->validate([
        'submission_id' => 'required|string',
        'managed' => 'required'
    ]);

    $submissionId = $validated['submission_id'];
    // ✅ Convertir a booleano con seguridad
    $managed = filter_var($validated['managed'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

    if (!$submissionId || !in_array($managed, [true, false], true)) {
        Log::warning("⛔ Invalid input values", [
            'submission_id' => $submissionId,
            'managed' => $validated['managed']
        ]);

        return response("⛔ Invalid input", 400);
    }

    Log::info("🔎 POST submission_id: $submissionId, managed: " . ($managed ? '1' : '0'));

    try {
        // ✅ Actualiza DB y JotForm
        $dbSuccess = JotformSuppliesRequest::updateManaged($submissionId, (int) $managed);
        $jotform = new JotformService();
        $jotformSuccess = $jotform->updateSubmissionField($submissionId, ["11" => (string) (int) $managed]);

        if ($dbSuccess && $jotformSuccess) {
            return response("✅ Update successful (DB & JotForm)", 200);
        } elseif ($dbSuccess || $jotformSuccess) {
            return response("⚠️ Partial update", 207);
        } else {
            return response("❌ Update failed", 500);
        }
    } catch (\Throwable $e) {
        Log::error("❌ Exception in updateManaged: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Server error'
        ], 500);
    }
}

    public function list()
    {
        try {
            $data = JotformSuppliesRequest::getAllFromView();
            return response()->json($data);
        } catch (\Throwable $e) {
			Log::error("❌ Exception in list: " . $e->getMessage(), [
				'trace' => $e->getTraceAsString()
			]);
			return response()->json([
				'success' => false,
				'error' => 'Server error'
			], 500);
		}
    }
}
