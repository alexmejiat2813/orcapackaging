<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\Sales\FormulaireService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class EstimateItemController extends Controller
{
    /**
     * Display the sales orders view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $options = [
            "sacsImpr" => "Sacs imprimés",
            "sacsNonImpr" => "Sacs non imprimés",
            "rouleaux" => "Rouleaux imprimés",
            "sacsPapier" => "Sacs en papier",
            "tape" => "Ruban adhésif"
        ];

        return view('sales.estimates_item', compact('options'));
    }

    public function getSession(){
        $idItem = Session::get('ID_Item');
        $reponse = response()->json([
            'has' => session()->has('ID_Item'),
            'id' => $idItem
        ]);
        Session::flash('ID_Item', $idItem);
        return $reponse;
    }

    public function storeItem(Request $request, FormulaireService $formulaireService) {
        $id = Session::get('ID_Item');
        $data = $request->all();
        $response = null;
        if (Session::has('ID_Item')) {
            $response = $formulaireService->handleUpdate($data, $id);
            Session::forget('ID_Item');
        } else {
            $response = $formulaireService->handleInsertion($data);
        }
        return response()->json($response);
    }

    public function gridData(): JsonResponse {
        $id = Session::get('ID_Soumission');

        $data = DB::table('ItemsSynology')
            ->join('ItemsSoumissionsSynology', 'ItemsSynology.ID', '=', 'ItemsSoumissionsSynology.ItemID')
            ->where('ItemsSoumissionsSynology.SoumissionID', $id)
            ->orderByDesc('ItemsSynology.ID')
            ->select('ItemsSynology.*')
            ->get();

        return response()->json($data);
    }

    public function modifier(Request $request) {
        $idItem = $request->input('ID');
        Session::flash('ID_Item', $idItem);

        $item = DB::table('ItemsSynology')->where('ID', $idItem)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item non trouvé'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    public function supprimer(Request $request) {
        $id = $request->input('ID');
        
        // 2. Supprimer les lignes de la table pivot
        DB::table('ItemsSoumissionsSynology')
            ->where('ItemID', $id)
            ->delete();
        
        // 3. Supprimer la soumission principale
        DB::table('ItemsSynology')
            ->where('ID', $id)
            ->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Item supprime avec succes',
            'deleted_item' => $id
        ]);
    }

    public function copier(Request $request) {
        $idItem = $request->input('ID'); // ID de l'objet a dupliquer
        $idSoumission = Session::get('ID_Soumission');

        DB::beginTransaction();

        try {
            // Recuperation de l'item
            $item = DB::table('ItemsSynology')->where('ID', $idItem)->first();
            
            $data = (array) $item;
            unset($data['ID']); // très important

            $newId = DB::table('ItemsSynology')->insertGetId($data);
            DB::table('ItemsSoumissionsSynology')->insert([
                'SoumissionID' => $idSoumission,
                'ItemID' => $newId
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Item dupliqué avec succès",
                'nouvel_item_id' => $newId
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication : ' . $e->getMessage()
            ], 500);
        }
    }
}

?>