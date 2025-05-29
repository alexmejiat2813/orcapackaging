<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EstimateController extends Controller
{
    /**
     * Display the sales orders view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clients = DB::table('Customer')->select('Customer_No', 'Customer_Name')->get();
        return view('sales.estimates', compact('clients')); 
    }

    public function gerer(Request $request) {
        // On récupère la donnée "ID" envoyée via JSON (POST)
        $id = $request->input('ID');

        // On la stocke dans la session Laravel
        Session::put('ID_Soumission', $id);

        // Optionnel : retourner une réponse JSON
        return response()->json([
            'message' => 'ID stored successfully',
            'ID_Soumission' => $id,
        ]);
    }

    public function supprimer(Request $request) {
        $id = $request->input('ID');

        // 1. Récupérer les ItemID avant de supprimer la relation
        $itemIds = DB::table('ItemsSoumissionsSynology')
            ->where('SoumissionID', $id)
            ->pluck('ItemID') // retourne une collection
            ->toArray();      // convertit en array PHP classique
        
        // 2. Supprimer les lignes de la table pivot
        DB::table('ItemsSoumissionsSynology')
            ->where('SoumissionID', $id)
            ->delete();
        
        // 3. Supprimer la soumission principale
        DB::table('SoumissionsSynology')
            ->where('ID', $id)
            ->delete();
        
        // 4. Supprimer les items liés
        if (!empty($itemIds)) {
            DB::table('ItemsSynology')
                ->whereIn('ID', $itemIds)
                ->delete();
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Soumission et données associées supprimées avec succès.',
            'deleted_items' => $itemIds
        ]);
    }

    public function copier(Request $request) {
        $idSoumission = $request->input('ID'); // ID de la soumission à dupliquer

        // 1. Valider les nouvelles infos de la soumission dupliquée
        $validated = $request->validate([
            'Client' => 'required|string|max:255',
            'Nom' => 'required|string|max:255',
            'Prenom' => 'required|string|max:255',
            'Email' => 'required|email',
            'Telephone' => 'nullable|string|max:50',
            'Nom_Travail' => 'nullable|string|max:255',
            'Date_Livraison' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // 2. Créer la nouvelle soumission
            $idNouvelleSoumission = DB::table('SoumissionsSynology')->insertGetId($validated);

            // 3. Trouver tous les ItemID liés à l’ancienne soumission
            $itemIdsOriginaux = DB::table('ItemsSoumissionsSynology')
                ->where('SoumissionID', $idSoumission)
                ->pluck('ItemID')
                ->toArray();

            // 4. Préparer la nouvelle liaison item <-> nouvelle soumission
            foreach ($itemIdsOriginaux as $itemId) {
                // 4.1 Récupérer les données originales
                $itemData = DB::table('ItemsSynology')->where('ID', $itemId)->first();

                // 4.2 Retirer l’ID pour l’insertion
                $itemArray = (array) $itemData;
                unset($itemArray['ID']);

                // 4.3 Insérer la copie
                $newItemId = DB::table('ItemsSynology')->insertGetId($itemArray);

                // 4.4 Insérer la relation
                DB::table('ItemsSoumissionsSynology')->insert([
                    'SoumissionID' => $idNouvelleSoumission,
                    'ItemID' => $newItemId
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Soumission dupliquée avec succès",
                'nouvelle_soumission_id' => $idNouvelleSoumission
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication : ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeSoumission(Request $request) {
        if (!$request->expectsJson()) {
            return response()->json(['error' => 'Cette requête doit être de type JSON'], 400);
        }

        $validated = $request->validate([
            'clients' => 'required|string|max:255',
            'nomClient' => 'required|string|max:255',
            'prenomClient' => 'required|string|max:255',
            'emailClient' => 'required|email|max:255',
            'telephoneClient' => 'required|string|max:50',
            'nomTravail' => 'required|string|max:255',
            'dateLivraisonSouhaitee' => 'required|date',
        ]);
        // Mapping vers les colonnes de la base de données
        $dataToInsert = [
            'Client' => $validated['clients'],
            'Nom' => $validated['nomClient'],
            'Prenom' => $validated['prenomClient'],
            'Email' => $validated['emailClient'],
            'Telephone' => $validated['telephoneClient'],
            'Nom_Travail' => $validated['nomTravail'],
            'Date_Livraison' => $validated['dateLivraisonSouhaitee'],
        ];

        if (Session::has('ID_Soumission')) {
            $id = Session::get('ID_Soumission');
            try {
                DB::table('SoumissionsSynology')->where('ID', $id)->update($dataToInsert);
                return response()->json([
                    'success' => true,
                    'message' => 'Modification réussie',
                ]);
            } catch (\Exception $e) {
                Log::error("Erreur de modification: " . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => "Erreur de modification: " . $e->getMessage()
                ], 500);
            }
        } else {
            try {
                DB::table('SoumissionsSynology')->insert($dataToInsert);

                return response()->json([
                    'success' => true,
                    'message' => 'Insertion réussie',
                ]);
            } catch (\Exception $e) {
                Log::error("Erreur d'insertion : " . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => "Erreur d'insertion : " . $e->getMessage()
                ], 500);
            }
        }
    }

    public function gridData(): JsonResponse {
        $soumissions = DB::table('SoumissionsSynology')
            ->orderByDesc('ID')
            ->get();

        return response()->json($soumissions);
    }

    public function modifier(Request $request) {
        $idSoumission = $request->input('ID');
        Session::flash('ID_Soumission', $idSoumission);

        $soumission = DB::table('SoumissionsSynology')->where('ID', $idSoumission)->first();

        if (!$soumission) {
            return response()->json(['success' => false, 'message' => 'Item non trouvé'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $soumission
        ]);
    }

    public function getSession(){
        $idSoumission = Session::get('ID_Soumission');
        $reponse = response()->json([
            'has' => session()->has('ID_Soumission'),
            'id' => $idSoumission
        ]);
        Session::flash('ID_Soumission', $idSoumission);
        return $reponse;
    }
}

?>