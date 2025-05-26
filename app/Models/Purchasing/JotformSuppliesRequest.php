<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JotformSuppliesRequest extends Model
{
    protected $table = 'JotformSuppliesRequest';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'jotform_id',
        'created_at',
        'machine',
        'description',
        'urgency',
        'stock_quantity',
        'notes',
        'managed',
        'files'
    ];

    public static function insertOrUpdateFromSubmission(array $submission)
    {
        try {
            $existing = self::where('jotform_id', $submission['submission_id'])->first();

            $data = [
                'jotform_id'     => $submission['jotform_id'],
                'created_at'     => Carbon::parse($submission['created_at']),
                'machine'        => $submission['machine'],
                'description'    => $submission['description'],
                'urgency'        => $submission['urgency'],
                'stock_quantity' => $submission['stock_quantity'],
                'notes'          => $submission['notes'],
                'managed'        => $submission['is_managed'],
                'files'          => is_array($submission['files']) ? json_encode($submission['files']) : $submission['files'],
            ];

            if ($existing) {
                if (self::isModified($existing, $data)) {
                    $existing->update($data);
                    Log::info("🟡 Updated submission ID: {$submission['submission_id']}");
                }
            } else {
                self::create(array_merge(['jotform_id' => $submission['submission_id']], $data));
                Log::info("🟢 Inserted new submission ID: {$submission['submission_id']}");
            }
        } catch (\Exception $e) {
            Log::error("❌ Error processing submission ID {$submission['submission_id']}: " . $e->getMessage());
        }
    }

    public static function isModified(self $existing, array $data): bool
    {
        foreach ($data as $key => $value) {
            $current = $existing->{$key};

            if (is_numeric($value)) {
                if ((float)$current !== (float)$value) return true;
            } elseif ($key === 'files') {
                if (json_decode($current, true) !== json_decode($value, true)) return true;
            } elseif ($current != $value) {
                return true;
            }
        }
        return false;
    }

    public static function updateManaged(string $submissionId, int $managed): bool
    {
        try {
            return self::where('jotform_id', $submissionId)->update(['managed' => $managed]) > 0;
        } catch (\Exception $e) {
            Log::error("❌ DB update error for jotform_id {$submissionId}: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllFromView(): array
    {
        try {
            return \DB::table('PHP_View_Jotform_Supplies_Request')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error("❌ Error fetching view data: " . $e->getMessage());
            return [];
        }
    }
}
