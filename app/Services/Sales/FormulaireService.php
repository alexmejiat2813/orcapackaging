<?php

namespace App\Services\Sales;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Exception;

class FormulaireService
{
    protected $table = 'ItemsSynology';

    public function handleInsertion(array $rawData): array
    {
        if (empty($rawData)) {
            return [
                'success' => false,
                'message' => 'Aucune donnée reçue'
            ];
        }

        // Générer les filtres
        $data = $this->sanitize($rawData);

        // Vérifier les colonnes et les créer si nécessaire
        $this->verifyColumns(array_keys($data));

        // Insertion
        try {
            $idItem = DB::table($this->table)->insertGetId($data);
            // Lier l'ID de la soumission avec les items
            DB::table('ItemsSoumissionsSynology')->insert([
                'SoumissionID' => session('ID_Soumission'),
                'ItemID' => $idItem,
            ]);
            return [
                'success' => true,
                'message' => 'Les données ont été insérées avec succès !'
            ];
        } catch (\Throwable $e) {
            Log::error("Erreur d'insertion: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'insertion : ' . $e->getMessage()
            ];
        }
    }

    public function handleUpdate(array $rawData, int $idItem): array
    {
        if (empty($rawData)) {
            return [
                'success' => false,
                'message' => 'Aucune donnée reçue'
            ];
        }

        // Générer les filtres
        $data = $this->sanitize($rawData);

        // Vérifier les colonnes et les créer si nécessaire
        $this->verifyColumns(array_keys($data));

        // Insertion
        try {
            DB::table($this->table)->where('ID', $idItem)->update($data);
            return [
                'success' => true,
                'message' => 'Les données ont été modifiées avec succès !'
            ];
        } catch (\Throwable $e) {
            Log::error("Erreur de modification: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification : ' . $e->getMessage()
            ];
        }
    }

    protected function sanitize(array $input): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            if (is_numeric($value)) {
                $sanitized[$key] = floatval($value);
            } else {
                $sanitized[$key] = strip_tags($value);
            }
        }

        return $sanitized;
    }

    protected function verifyColumns(array $columns): void
    {
        foreach ($columns as $column) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                throw new Exception("Nom de colonne invalide : $column");
            }

            if (!Schema::hasColumn($this->table, $column)) {
                DB::statement("ALTER TABLE {$this->table} ADD [$column] NVARCHAR(255)");
                Log::info("Colonne ajoutée dynamiquement : $column");
            }
        }
    }
}
?>
