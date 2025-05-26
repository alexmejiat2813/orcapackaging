<?php

namespace App\Services\Jotform;

use Illuminate\Support\Facades\Log;

class JotformValidationsService
{
    public static function validate(array $submission): bool
    {
        if (!self::requiredFieldsPresent($submission)) {
            Log::warning("Missing required fields", $submission);
            return false;
        }

        if (!is_numeric($submission['stock_quantity'])) {
            Log::warning("Invalid stock_quantity", ['value' => $submission['stock_quantity']]);
            return false;
        }

        if (!self::validateFiles($submission['files'] ?? '[]')) {
            Log::warning("Invalid file extensions", ['files' => $submission['files']]);
            return false;
        }

        return true;
    }

    private static function requiredFieldsPresent(array $data): bool
    {
        $required = ['submission_id', 'created_at', 'machine', 'description', 'urgency', 'stock_quantity'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }

    private static function validateFiles(string $files): bool
    {
        $array = is_string($files) ? json_decode($files, true) : [];

        if (!is_array($array)) return false;

        foreach ($array as $file) {
            if (!preg_match('/\.(jpg|jpeg|png|pdf)$/i', $file)) {
                return false;
            }
        }

        return true;
    }
}
