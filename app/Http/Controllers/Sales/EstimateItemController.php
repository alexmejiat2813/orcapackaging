<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\Sales\FormulaireService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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
        $cylindres = DB::table('Cylinder')->select('Cylinder_ID', 'Cylinder_Name')->get();
        $transports = DB::table('Transport')->select('Transp_Description')->get();
        return view('sales.estimates_item', compact('options', 'cylindres', 'transports'));
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
        $data = $request->all();
        $response = null;
        if (Session::has('ID_Item')) {
            $response = $formulaireService->handleUpdate($data, Session::get('ID_Item'));
            EstimateItemController::updateRequest(Session::get('ID_Item'));
        } else {
            $response = $formulaireService->handleInsertion($data);
            EstimateItemController::addRequest(Session::get('ID_Item'));
        }
        Session::forget('ID_Item');
        return response()->json($response);
    }

    public function gridData(): JsonResponse {
        $id = Session::get('ID_Soumission');

        $data = DB::table('ItemsSynology')
            ->join('ItemsSoumissionsSynology', 'ItemsSynology.ID', '=', 'ItemsSoumissionsSynology.ItemID')
            ->where('ItemsSoumissionsSynology.SoumissionID', $id)
            ->orderByDesc('ItemsSynology.ID')
            ->select('ItemsSynology.ID','ItemsSynology.descriptionProduit','ItemsSynology.quantite','ItemsSynology.commande','ItemsSynology.isReady', )
            ->get()
            ->map(function ($item) {
                $item->isReady = (bool) $item->isReady; // 🔥 conversion ici
                return $item;
            });

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

        // 1. Verification de la colonne isReady
        $item = DB::table('ItemsSynology')
            ->where('ID', $id)
            ->first();

        if ($item && $item->IsReady) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de supprimer : l'item est marqué comme prêt."
            ], 400);
        }
        
        // 2. Supprimer les lignes de la table pivot
        DB::table('ItemsSoumissionsSynology')
            ->where('ItemID', $id)
            ->delete();
        
        // 3. Supprimer la soumission principale
        DB::table('ItemsSynology')
            ->where('ID', $id)
            ->delete();

        EstimateItemController::deleteRequest($id);
    
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
            // Changement de la colonne isReady
            $data['IsReady'] = 0;

            $newId = DB::table('ItemsSynology')->insertGetId($data);
            DB::table('ItemsSoumissionsSynology')->insert([
                'SoumissionID' => $idSoumission,
                'ItemID' => $newId
            ]);

            DB::commit();

            EstimateItemController::addRequest($newId); // ici

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

    public function itemReady(Request $request) {
        $idItem = $request->input('id');
        $userId = Auth::id();

        DB::beginTransaction();

        // On recupere l'item
        $item = DB::table('ItemsSynology as i')
            ->join('ItemsSoumissionsSynology as is', 'i.ID', '=', 'is.ItemID')
            ->join('SoumissionsSynology as s', 'is.SoumissionID', '=', 's.ID')
            ->where('i.ID', $idItem)
            ->first();


        // On prend la request pour pouvoir remettre les champs existants
        $requestID = DB::table('ItemsSynologyEstMaster')
            ->where('ItemID', $idItem)
            ->value('Request_ID');

        $request = DB::table('Request_Detail')
            ->where('Request_ID', $requestID)
            ->first();

        // On update Request pour indiquer qu'elle est transmise
        DB::table('Request')
            ->where('Request_ID', $requestID)
            ->update([
                'Request_Transmit' => 1,
                'Request_Transmit_Time_Stamp' => DB::raw('GETDATE()')
            ]);

        // Étape 1 : Marquer l'item comme prêt
        DB::table('ItemsSynology')->where('ID', $idItem)->update([
            'isReady' => 1
        ]);

        // Étape 2 : Créer une ligne vide dans Estimation_Detail
        $estimationID = DB::table('Estimation_Detail')->insertGetId([
        ]);

        // Étape 3 : Créer une ligne dans Estimation en liant avec Estimation_Detail
        DB::table('Estimation')->insert([
            'Est_ID' => $estimationID,
            'Est_Cancel' => 0,
            'Est_Time_Stamp' => DB::raw('GETDATE()'), // A REVOIR
            'Est_Transmit' => 1,
            'Est_Users_ID' => $userId,
            'Est_Lock' => 0
        ]);

        // Étape 4 : Créer une ligne dans Quotation_Detail
        $quotationID =  DB::table('Quotation_Detail')->insertGetId([
            'Art_Price' => 0,
            'Art_Time' => 0,
            'Art_Total_1' => 0,
            'Art_Total_2' => 0,
            'Art_Total_3' => 0,
            'Art_Total_4' => 0,
            'Art_Total_5' => 0,
            'Art_Total_6' => 0,
            'Color_Key_Price' => 0,
            'Color_Key_Qty' => 0,
            'Color_Key_Total_1' => 0,
            'Color_Key_Total_2' => 0,
            'Color_Key_Total_3' => 0,
            'Color_Key_Total_4' => 0,
            'Color_Key_Total_5' => 0,
            'Color_Key_Total_6' => 0,
            'Color_Specification' => $request->Color_Specification,
            'Deliveray_Delay' => $request->Deliveray_Delay,
            'Die_Price' => 0,
            'Die_Qty' => 0,
            'Die_Total_1' => 0,
            'Die_Total_2' => 0,
            'Die_Total_3' => 0,
            'Die_Total_4' => 0,
            'Die_Total_5' => 0,
            'Die_Total_6' => 0,
            'Display_1' => 1,
            'Display_2' => 0,
            'Display_3' => 0,
            'Display_4' => 0,
            'Display_5' => 0,
            'Display_6' => 0,
            'Films_Price' => 0,
            'Films_Qty' => 0,
            'Films_Total_1' => 0,
            'Films_Total_2' => 0,
            'Films_Total_3' => 0,
            'Films_Total_4' => 0,
            'Films_Total_5' => 0,
            'Films_Total_6' => 0,
            'GMG_Price' => 0,
            'GMG_Qty' => 0,
            'GMG_Total_1' => 0,
            'GMG_Total_2' => 0,
            'GMG_Total_3' => 0,
            'GMG_Total_4' => 0,
            'GMG_Total_5' => 0,
            'GMG_Total_6' => 0,
            'Laser_Price' => 0,
            'Laser_Qty' => 0,
            'Laser_Total_1' => 0,
            'Laser_Total_2' => 0,
            'Laser_Total_3' => 0,
            'Laser_Total_4' => 0,
            'Laser_Total_5' => 0,
            'Laser_Total_6' => 0,
            'Other_Fee_Total_1' => 0,
            'Other_Fee_Total_2' => 0,
            'Other_Fee_Total_3' => 0,
            'Other_Fee_Total_4' => 0,
            'Other_Fee_Total_5' => 0,
            'Other_Fee_Total_6' => 0,
            'Paper_Description_1' => '',
            'Paper_Description_2' => '',
            'Paper_Description_3' => '',
            'Paper_Description_4' => '',
            'Paper_Description_5' => '',
            'Paper_Description_6' => '',
            'Plate_Price' => 0,
            'Plate_Qty' => 0,
            'Plate_Total_1' => 0,
            'Plate_Total_2' => 0,
            'Plate_Total_3' => 0,
            'Plate_Total_4' => 0,
            'Plate_Total_5' => 0,
            'Plate_Total_6' => 0,
            'Quantity_1' => $request->R_Qty_1,
            'Quantity_2' => 0,
            'Quantity_3' => 0,
            'Quantity_4' => 0,
            'Quantity_5' => 0,
            'Quantity_6' => 0,
            'Quotation_Comment' => $request->Request_Comment,
            'Quotation_Description' => $request->Request_Description,
            'Total_Commission_1' => 0,
            'Total_Commission_2' => 0,
            'Total_Commission_3' => 0,
            'Total_Commission_4' => 0,
            'Total_Commission_5' => 0,
            'Total_Commission_6' => 0,
            'Total_Price_1' => $item->prixFinauxMilleAvecProfit,
            'Total_Price_2' => 0,
            'Total_Price_3' => 0,
            'Total_Price_4' => 0,
            'Total_Price_5' => 0,
            'Total_Price_6' => 0,
            'Unit_Price_1' => $item->prixFinauxUniteAvecProfit,
            'Unit_Price_2' => 0,
            'Unit_Price_3' => 0,
            'Unit_Price_4' => 0,
            'Unit_Price_5' => 0,
            'Unit_Price_6' => 0,
            'Varnish_Specification' => '',
            'Shipping_AD' => $request->Shipping_AD,
            'Dossier_Precedent' => $request->Dossier_Precedent,
            'Autre' => '',
            'Random_Id' => -1,
            'Quantity_Factor_ID' => 1,
            'Asset_Id' => $request->Asset_Id,
            'have_Eye_Mark' => $request->have_Eye_Mark
        ]);

        // Étape 5 : Créer une ligne dans Quotation
        DB::table('Quotation')->insert([
            'Quotation_ID' => $quotationID,
            'Quotation_Time_Stamp' => DB::raw('GETDATE()'), // A REVOIR
            'Quotation_Transmit' => 1,
            'Quotation_Transmit_Time_Stamp' => DB::raw('GETDATE()'), // A REVOIR
            'Quotation_Cancel' => 0,
            'Quotation_Users_ID' => $userId,
            'Quotation_Lock' => 0,
            'Quotation_Revision' => 1
        ]);

        // Étape 6 : Mise à jour de ItemsSynologyEstMaster
        DB::table('ItemsSynologyEstMaster')
            ->where('ItemID', $idItem)
            ->update([
                'Estimation_ID' => $estimationID,
                'Quotation_ID' => $quotationID
            ]);

        $customer = DB::table('Customer')
            ->where('Customer_No', $item->Client)
            ->first();

        $asset = DB::table('Asset')
            ->where('Asset_ID', $item->Asset_ID)
            ->first();
        
        // Ajout de toutes les informations dans la table Est_Master
        DB::table('Est_Master')->insert([
            'Request_ID' => $request->Request_ID,
            'Est_ID' => $estimationID,
            'Quotation_ID' => $quotationID,
            'Customer_No' => $item->Client,
            'Customer_Name' => $customer->Customer_Name ?? 'Inconnu',
            'Customer_Contact' => ($asset?->Asset_FName ?? '') . ' ' . ($asset?->Asset_Name ?? ''),
            'Rep_No' => $customer->Rep_No ?? 1,
            'Rep_Name' => $customer->Rep_Name ?? 'SoniVo',
            'CuPriceLevel' => 0,
            'Col' => 0,
            'InInvoiceNumber' => -1,
            'Commande_Id' => -1,
            'Customer_Id' => $customer->Customer_ID ?? -1,
            'Press_Type_Id' => $request->Work_Type
        ]);   

        // Preparation du champ InInvoiceNumber
        $param = DB::table('ParamKey')
            ->where('Champ', 'InInvoiceNumber')
            ->lockForUpdate()
            ->first();
        $invoiceNumber = $param->Valeur;

        // Preparation du prix total
        $subTotal = $item->quantite * $item->prixFinauxMilleAvecProfit;

        // Ajout des informations dans Commande
        DB::table('Commande')->insert([
            'Customer_Code' => $item->Client,
            'Expedie_Tel' => "_(___)___-____",
            'Expedie_Fax' => "_(___)___-____",
            'Date_Commande' => DB::raw('GETDATE()'), // A REVOIR
            'Date_Demander' => DB::raw('GETDATE()'), // A REVOIR
            'Date_Expedition' => DB::raw('GETDATE()'), // A REVOIR
            'Respecter_date' => 1,
            'PO_Client' => $item->poClient,
            'Transporteur' => $item->transport,
            'Compte_Transporteur' => $item->compteTransporteur,
            'Acheteur' => ($asset?->Asset_FName ?? '') . ' ' . ($asset?->Asset_Name ?? ''),
            'Terme_paiement_Id' => 23,
            'Libre1' => $item->totalPieds ?? '',
            'Commentaire' => $item->commentaire,
            'Note' => '',
            'Creer_Par' => $userId,
            'Creer_Date' => DB::raw('GETDATE()'), // A REVOIR
            'Credit_Autorise' => 0,
            'Transmit' => 1,
            'Transmit_Par' => $userId,
            'Transmit_Date' => DB::raw('GETDATE()'), // A REVOIR
            'Cancel' => 0,
            'Complet' => 0,
            'Random_Id' => -1,
            'Qty_Id' => 0,
            'InInvoiceNumber' => $invoiceNumber, 
            'Commande_Status_id' => 1,
            'Rep_No' => $customer->Rep_No ?? 1,
            'Rep_Name' => $customer->Rep_Name ?? 'SoniVo',
            'Terme_Paiement_Desc' => 'Net 30 jours',
            'Customer_Name' => $customer->Customer_Name ?? 'Inconnu',
            'TPS' => round($subTotal * 0.05, 2),
            'TVQ' => round($subTotal * 0.0975, 2),
            'SubTotal' => round($subTotal, 2),
            'Total' => round($subTotal + ($subTotal * 0.05) + ($subTotal * 0.0975) , 2),
            'Frais_Importe' => 0,
            'Status_Fabrication_Id' => 1,
            'A_livrer' => 0,
            'Credit_To_Approuve' => 0,
            'Customer_Id' => $customer->Customer_ID ?? -1,
            'Com_Autre' => '',
            'Com_Expedition' => '',
            'Com_Finition' => '',
            'Com_Pre_Presse' => '',
            'Com_Presse' => '',
            'Com_Sous_Traitance' => '', 
            'Is_Charged' => 0,
            'isReady_Production' => 0,
            'Warning' => 0,
            'RnD' => 1,
            'RnD_TimeStamp' => DB::raw('GETDATE()'), // A REVOIR
            'RnD_User_Id' => $userId,
            'Production_Complete' => 0,
            'Commande_Customer_Id' => 0,
            'Commande_Lock' => 0,
            'Commande_Transmit_First' => 0,
            'TPS_Description' => 'TPS : 5 %',
            'TVQ_Description' => 'TVQ : 9.975 %',
            'Work_Type_ID' => $request->Work_Type,
            'Asset_Id' => $item->Asset_ID,
            'Confirmer_Date' => 1,
            'Confirm_date_email_sent' => 0,
            'Expedie_Adress2' => '',
            'Divisions_Id' => 1
        ]);

        DB::table('ParamKey')
            ->where('Champ', 'InInvoiceNumber')
            ->update(['Valeur' => $invoiceNumber + 1]);

        DB::commit();

        return response()->json(['message' => 'Item marked as ready and linked to estimation & quotation.']);
    }

    // Fonctions pour ajout Database Thomas

    // Ajoute le dernier item qui a ete ajoute
    public static function addRequest(int $itemID) {
        try {
            $user_id = Auth::id();
            $item = DB::table('ItemsSynology')->where('ID', $itemID)->first();
            $soumissionID = DB::table('ItemsSoumissionsSynology')->where('ItemID', $itemID)->first();
            $soumission = DB::table('SoumissionsSynology')->where('ID', $soumissionID->SoumissionID ?? null)->first();
            
            DB::beginTransaction();
            // Ajout dans la table Request
            if ($user_id) {
                $colorSpec = collect([
                    $item->couleur1 ?? null,
                    $item->couleur2 ?? null,
                    $item->couleur3 ?? null,
                    $item->couleur4 ?? null,
                    $item->couleur5 ?? null,
                ])->filter()->implode(', ');
                $request_id = DB::table('Request_Detail')->insertGetId([
                    'Work_Type' => match($item->commande) {
                        'sacsImpr' => 5,
                        'sacsNonImpr' => 6,
                        'rouleaux' => 7,
                        'tape' => 8,
                        'sacsPapier' => 9,
                        default => 1,
                    },
                    'Deliveray_Delay' => is_null($soumission->Date_Livraison) || !strtotime($soumission->Date_Livraison)
                        ? 30
                        : (int) now()->diffInDays(\Carbon\Carbon::parse($soumission->Date_Livraison), false),
                    'Tolerance' => $item->tolerance,
                    'Request_Comment' => $item->commentaire,
                    'Request_Description' => $item->descriptionProduit,
                    'Color_Specification' => $colorSpec,
                    'Qty_Lot' => 1,
                    'Qty_Plate' => $item->nbEncres,
                    'Qty_Color' => $item->nbEncres,
                    'Laminated' => 0,
                    'R_Qty_1' => $item->quantite,
                    'R_Qty_2' => 0,
                    'R_Qty_3' => 0,
                    'R_Qty_4' => 0,
                    'R_Qty_5' => 0,
                    'R_Qty_6' => 0,
                    'Rewind_Qty' => 0,
                    'Shrink' => 0,
                    'Shrink_Qty' => 0,
                    'Sheet_Qty_Label' => 0,
                    'Sheet_Qty' => 0,
                    'Fold_Qty_Label' => $item->totalSacsParPalette ?? $item->totalImpressionsParPalette ?? 0,
                    'Fold_Qty' => 0,
                    'Application_Id' => 1,
                    'Cut_Id' => 0,
                    'Shipping_AD' => $item->quiVaLivrer,
                    'Price_Factor_ID' => match($item->typeFacturation) {
                        'unite' => 1,
                        'mille' => 2,
                        default => null,
                    },
                    'Dossier_Precedent' => $item->dossierPrecedent,
                    'Quantity_Factor_Id' => 1,
                    'Random_Id' => -1,
                    'Die_Across_Metric' => $item->hauteur,
                    'Asset_Id' => $soumission->Asset_ID,
                    'CMYK' => 0,
                    'Eye_Mark_Id' => match($item->imprimeEyeMark) {
                        'plain' => 2,
                        'imprime-orca' => 1,
                        default => 0,
                    },
                    'have_Eye_Mark' => $item->imprimeEyeMark ? 1 : 0,
                    'is_From_Estimation' => 0,
                    'Pms' => 0,
                    'Special' => 0,
                    'Varnish_Ink' => 0,
                    'Across_Qty' => 1,
                    'Cylinder_Id1' => $item->cylindre,
                    'Destination_Id' => 1,
                    'Die_Espace_Across_Metric' => $item->web,
                    'Die_Espace_Around_Metric' => $item->largeur,
                    'Disposition_Ar' => 1,
                    'Est_Commission' => $item->commission,
                    'Est_Profit' => $item->profit,
                    'Expedition_Fee_Id' => 1,
                    'Fanifoild' => 0,
                    'Glue_Print' => 0,
                    'have_Numerator' => 0,
                    'Paper_ID4' => match($item->commande) {
                        'sacsImpr', 'sacsNonImpr' => 23542,
                        'rouleaux' => -1,
                        'tape' => 3,
                        'sacsPapier' => 4,
                        default => 1,
                    },
                    'Paper_ID5' => $item->typePalette,
                    'Paper_ID6' => 0,
                    'Plate_ID' => 0,
                    'Press_ID' => match($item->commande) {
                        'sacsImpr' => 1,
                        'sacsNonImpr' => 2,
                        'rouleaux' => 1,
                        'tape' => 3,
                        'sacsPapier' => 4,
                        default => 1,
                    },
                    'Roll_Qty' => 0,
                    'Sheeter' => 0,
                    'Slitter_Qty' => 0,
                    'Package_Qty' => 0,
                    'Thickness' => $item->epaisseur,
                    'Material_Price' => 0,
                    'Box_Qty' => $item->nbBoites,
                    'Form_Type_id' => $item->niveauDifficulte,
                    'Printing_Percent_Waste' => $item->tolerance,
                    'Fret_Shipping' => 0,
                    'Color_Num_Id' => -1,
                    'Is_Cutting_Num' => 0,
                    'have_Cutter_Num' => 0,
                    'Bleed' => 0,
                    'Extra_Bleed' => 0,
                    'Material_Price2' => 0,
                    'Material_Price3' => 0,
                    'Material_Price4' => 0,
                    'Material_Price5' => 0,
                    'Material_Price6' => 0,
                    'Pellicle_Product_Color_id' => 0,
                    'Laminated_cost' => $item->coutBoite,
                    'Special_Instruction_Printing' => $item->coutPalette,
                    'Eye_Mark_Id2' => $item->coutTotauxProductionLivraison,
                    'Eye_Mark_id3' => 0,
                    'Eye_Mark_Other' => 0,
                    'have_UPC' => $item->upc ? 1 : 0,
                    'UPC_Note' => $item->upc ?: null,
                    'Printing_Efficiency' => $item->piedsParHeure ?: 0,
                    'Ok_Press_Description' => $item->quiVaInspecter,
                    'Ok_Press' => $item->validationPresse === 'non' || $item->validationPresse === null ? 0 : 1,
                    'Sac_Utilisation' => 1,
                    'Scellage_Conversion_Id' => 1,
                    'Poigne_Conversion_Id' => $item->poignee > 0 ? 1 : null,
                    'Ventilation_Have_Hole' => $item->nbTrousAeration > 0 ? 1 : 0,
                    'Ventilation_Hole_Qty_Id' => $item->nbTrousAeration,
                    'Ventilation_Hole_Diameter_Id' => match($item->diametreTrous) {
                        '1/8' => 1,
                        '1/4' => 2,
                        '3/8' => 3,
                        '1/2' => 4,
                        default => null,
                    },
                    'Ventilation_Hole_Position' => $item->positionTrous,
                    'Pellicle_Product_Id' => 1,
                    'Pellicle_Product_Cost' => $item->coutParLivre,
                ]);

                DB::table('Request')->insert([
                    'Request_ID' => $request_id,
                    'Request_Time_Stamp' => DB::raw('GETDATE()'), // A REVOIR
                    'Request_Transmit' => 0,
                    'Request_Cancel' => 0,
                    'Request_Users_ID' => $user_id,
                    'Request_Lock' => 0
                ]);

                DB::table('ItemsSynologyEstMaster')->insert([
                    'ItemID' => $itemID,
                    'Request_ID' => $request_id
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            Log::error('Erreur dans addRequest: ' . $e->getMessage());
            throw $e; 
        }
    }

    public static function updateRequest(int $itemID) {
        $item = DB::table('ItemsSynology')->where('ID', $itemID)->first();
        $soumissionID = DB::table('ItemsSoumissionsSynology')->where('ItemID', $itemID)->first();
        $soumission = DB::table('SoumissionsSynology')->where('ID', $soumissionID->SoumissionID ?? null)->first();

        $estMaster = DB::table('ItemsSynologyEstMaster')->where('ItemID', $itemID)->first();
        if (!$estMaster) {
            return; // Rien à mettre à jour
        }

        $request_id = $estMaster->Request_ID;

        if ($request_id) {
            $colorSpec = collect([
                $item->couleur1 ?? null,
                $item->couleur2 ?? null,
                $item->couleur3 ?? null,
                $item->couleur4 ?? null,
                $item->couleur5 ?? null,
            ])->filter()->implode(', ');

            DB::table('Request_Detail')->where('Request_ID', $request_id)->update([
                'Work_Type' => match($item->commande) {
                    'sacsImpr' => 5,
                    'sacsNonImpr' => 6,
                    'rouleaux' => 7,
                    'tape' => 8,
                    'sacsPapier' => 9,
                    default => 1,
                },
                'Deliveray_Delay' => is_null($soumission->Date_Livraison) || !strtotime($soumission->Date_Livraison)
                    ? 30
                    : (int) now()->diffInDays(\Carbon\Carbon::parse($soumission->Date_Livraison), false),
                'Tolerance' => $item->tolerance,
                'Request_Comment' => $item->commentaire,
                'Request_Description' => $item->descriptionProduit,
                'Color_Specification' => $colorSpec,
                'Qty_Plate' => $item->nbEncres,
                'Qty_Color' => $item->nbEncres,
                'R_Qty_1' => $item->quantite,
                'Fold_Qty_Label' => $item->totalSacsParPalette ?? $item->totalImpressionsParPalette ?? 0,
                'Shipping_AD' => $item->quiVaLivrer,
                'Price_Factor_ID' => match($item->typeFacturation) {
                    'unite' => 1,
                    'mille' => 2,
                    default => null,
                },
                'Dossier_Precedent' => $item->dossierPrecedent,
                'Die_Across_Metric' => $item->hauteur,
                'Asset_Id' => $soumission->Asset_ID,
                'Eye_Mark_Id' => match($item->imprimeEyeMark) {
                    'plain' => 2,
                    'imprime-orca' => 1,
                    default => 0,
                },
                'have_Eye_Mark' => $item->imprimeEyeMark ? 1 : 0,
                'Cylinder_Id1' => $item->cylindre,
                'Die_Espace_Across_Metric' => $item->web,
                'Die_Espace_Around_Metric' => $item->largeur,
                'Est_Commission' => $item->commission,
                'Est_Profit' => $item->profit,
                'Paper_ID4' => match($item->commande) {
                    'sacsImpr', 'sacsNonImpr' => 23542,
                    'rouleaux' => -1,
                    'tape' => 3,
                    'sacsPapier' => 4,
                    default => 1,
                },
                'Paper_ID5' => $item->typePalette,
                'Press_ID' => match($item->commande) {
                    'sacsImpr' => 1,
                    'sacsNonImpr' => 2,
                    'rouleaux' => 1,
                    'tape' => 3,
                    'sacsPapier' => 4,
                    default => 1,
                },
                'Thickness' => $item->epaisseur,
                'Box_Qty' => $item->nbBoites,
                'Form_Type_id' => $item->niveauDifficulte,
                'Printing_Percent_Waste' => $item->tolerance,
                'Laminated_cost' => $item->coutBoite,
                'Special_Instruction_Printing' => $item->coutPalette,
                'Eye_Mark_Id2' => $item->coutTotauxProductionLivraison,
                'have_UPC' => $item->upc ? 1 : 0,
                'UPC_Note' => $item->upc ?: null,
                'Printing_Efficiency' => $item->piedsParHeure ?: 0,
                'Ok_Press_Description' => $item->quiVaInspecter,
                'Ok_Press' => $item->validationPresse === 'non' || $item->validationPresse === null ? 0 : 1,
                'Poigne_Conversion_Id' => $item->poignee > 0 ? 1 : null,
                'Ventilation_Have_Hole' => $item->nbTrousAeration > 0 ? 1 : 0,
                'Ventilation_Hole_Qty_Id' => $item->nbTrousAeration,
                'Ventilation_Hole_Diameter_Id' => match($item->diametreTrous) {
                    '1/8' => 1,
                    '1/4' => 2,
                    '3/8' => 3,
                    '1/2' => 4,
                    default => null,
                },
                'Ventilation_Hole_Position' => $item->positionTrous,
                'Pellicle_Product_Cost' => $item->coutParLivre,
            ]);
        }
    }

    public static function deleteRequest(int $itemID) {
        // On récupère le Request_ID lié à l'item
        $request = DB::table('ItemsSynologyEstMaster as em')
            ->join('Request as r', 'em.Request_ID', '=', 'r.Request_ID')
            ->where('em.ItemID', $itemID)
            ->select('r.Request_ID')
            ->first();

        if ($request) {
            // Mise à jour des champs d'annulation dans la table Request
            DB::table('Request')
                ->where('Request_ID', $request->Request_ID)
                ->update([
                    'Request_Cancel' => 1,
                    'Request_Cancel_Time_Stamp' => DB::raw('GETDATE()')
                ]);

            return response()->json([
                'success' => true,
                'message' => "Requête annulée avec succès pour l'item ID $itemID"
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Aucune requête trouvée pour cet item"
        ], 404);
    }
}

?>