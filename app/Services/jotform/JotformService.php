<?php

namespace App\Services\Jotform;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JotformService
{
    protected string $baseUrl = 'https://api.jotform.com';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.jotform.api_key');
    }

    public function fetchSubmissions(string $formId): array
    {
        $url = "{$this->baseUrl}/form/{$formId}/submissions";

        $response = Http::get($url, [
            'apiKey' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $this->transformSubmissions($response->json('content') ?? []);
        }

        Log::error("❌ Failed to fetch JotForm submissions: " . $response->body());
        return [];
    }

    public function fetchSingleSubmission(string $submissionId): ?array
    {
        $response = Http::get("{$this->baseUrl}/submission/{$submissionId}", [
            'apiKey' => $this->apiKey
        ]);

        if ($response->successful()) {
            return $this->transformSingleSubmission($response->json('content'));
        }

        Log::error("❌ Failed to fetch JotForm submission $submissionId: " . $response->body());
        return null;
    }

    public function updateSubmissionField(string $submissionId, array $fields): bool
    {
        $postData = [];

        foreach ($fields as $qid => $value) {
            $postData["answers[{$qid}]"] = $value;
        }

        $response = Http::asForm()->post("{$this->baseUrl}/submission/{$submissionId}", array_merge([
            'apiKey' => $this->apiKey,
        ], $postData));

        if ($response->successful()) {
            Log::info("✅ Updated JotForm submission $submissionId");
            return true;
        }

        Log::error("❌ Failed to update JotForm submission $submissionId: " . $response->body());
        return false;
    }

    private function transformSubmissions(array $submissions): array
    {
        return array_map(fn($item) => $this->transformSingleSubmission($item), $submissions);
    }

    public function transformSingleSubmission(array $item): array
    {
        $answers = $item['answers'] ?? [];

        return [
            'submission_id'   => $item['id'] ?? $item['submissionID'],
            'form_id'         => $item['form_id'] ?? $item['formID'] ?? '',
            'created_at'      => $item['created_at'] ?? now(),
            'machine'         => $answers['3']['answer'] ?? '',
            'description'     => $answers['4']['answer'] ?? '',
            'urgency'         => $answers['6']['answer'] ?? '',
            'stock_quantity'  => $answers['9']['answer'] ?? '',
            'notes'           => $answers['7']['answer'] ?? '',
            'files'           => json_encode($answers['10']['answer'] ?? []),
            'is_managed'      => ($answers['11']['answer'] ?? '0') === '1' ? 1 : 0
        ];
    }
}
