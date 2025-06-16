<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Sales\EstimateItemController;

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
        $assets = DB::table('Asset')->select('Asset_ID' ,'Asset_FName', 'Asset_Name', 'SRPhoneNumber1', 'SREMail')->get();
        return view('sales.estimates', compact('clients', 'assets')); 
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

        // 4. Supprimer les items dans les request
        foreach ($itemIds as $id) {
            EstimateItemController::deleteRequest($id);
        }    
        
        // 5. Supprimer les items liés
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

        DB::beginTransaction();

        try {
            // 1. Récupérer les données de la soumission existante
            $soumissionOriginale = DB::table('SoumissionsSynology')->where('ID', $idSoumission)->first();

            if (!$soumissionOriginale) {
                return response()->json([
                    'success' => false,
                    'message' => "Soumission introuvable avec l'ID $idSoumission"
                ], 404);
            }

            // 2. Préparer les données à insérer
            $dataToInsert = [
                'Client' => $soumissionOriginale->Client,
                'Asset_ID' => $soumissionOriginale->Asset_ID,
                'Nom' => $soumissionOriginale->Nom,
                'Prenom' => $soumissionOriginale->Prenom,
                'Email' => $soumissionOriginale->Email,
                'Telephone' => $soumissionOriginale->Telephone,
                'Nom_Travail' => $soumissionOriginale->Nom_Travail,
                'Date_Livraison' => $soumissionOriginale->Date_Livraison,
            ];

            // 3. Créer la nouvelle soumission
            $idNouvelleSoumission = DB::table('SoumissionsSynology')->insertGetId($dataToInsert);

            // 4. Trouver tous les ItemID liés à l’ancienne soumission
            $itemIdsOriginaux = DB::table('ItemsSoumissionsSynology')
                ->where('SoumissionID', $idSoumission)
                ->pluck('ItemID')
                ->toArray();

            // 5. Dupliquer les items et les relier à la nouvelle soumission
            foreach ($itemIdsOriginaux as $itemId) {
                // 5.1 Récupérer les données originales
                $itemData = DB::table('ItemsSynology')->where('ID', $itemId)->first();

                if (!$itemData) {
                    continue; // on skippe les items manquants
                }

                // 5.2 Retirer l’ID pour l’insertion
                $itemArray = (array) $itemData;
                unset($itemArray['ID']);
                $itemArray['IsReady'] = 0; // forcé à non prêt

                // 5.3 Insérer la copie
                $newItemId = DB::table('ItemsSynology')->insertGetId($itemArray);

                // 5.4 Insérer la relation
                DB::table('ItemsSoumissionsSynology')->insert([
                    'SoumissionID' => $idNouvelleSoumission,
                    'ItemID' => $newItemId
                ]);

                // 5.5 Ajouter les nouveaux items dans les tables Request
                EstimateItemController::addRequest($newItemId);
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
            'assets' => 'required|string|max:255',
            'nomClient' => 'nullable|string|max:255',
            'prenomClient' => 'nullable|string|max:255',
            'emailClient' => 'nullable|email|max:255',
            'telephoneClient' => 'nullable|string|max:50',
            'nomTravail' => 'required|string|max:255',
            'dateLivraisonSouhaitee' => 'required|date',
        ]);

        // Mapping vers les colonnes de la base de données
        $dataToInsert = [
            'Client' => $validated['clients'],
            'Asset_ID' => $validated['assets'],
            'Nom' => $validated['nomClient'] ?? null,
            'Prenom' => $validated['prenomClient'] ?? null,
            'Email' => $validated['emailClient'] ?? null,
            'Telephone' => $validated['telephoneClient'] ?? null,
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
        $soumissions = DB::table('SoumissionsSynology as s')
            ->leftJoin('Asset as a', 's.Asset_ID', '=', 'a.Asset_ID')
            ->orderByDesc('s.ID')
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