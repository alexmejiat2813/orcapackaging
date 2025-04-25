<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Production\Commande;

class BomController extends Controller
{

public function index()
    {
        $requests = Commande::all(); // Retrieve all supply requests
        return view('production.bom', compact('requests'));
    }
    /**
     * Return BOM details for a given Lot_ID.
     */
    public function getDetails($lotId)
    {
        $result = DB::select(DB::raw("
            SELECT 
                cmc.Material_Code,
                cmc.Material_Quantity AS Estimated_Quantity,
                0 AS Consumed_Quantity, -- De momento lo dejamos a 0 para simplificar
                0 AS Order_Quantity,    -- De momento lo dejamos a 0 para simplificar
                0 AS Stock_Initial,     -- De momento lo dejamos a 0 para simplificar
                u.PrLongUnitCode
            FROM ThomasOrca.dbo.CommandeMaterialCheck cmc
            LEFT JOIN ThomasOrca.dbo.Unit_Measurement u ON cmc.Unit_Measurement_ID = u.Unit_Measurement_ID
            WHERE cmc.Schedule_ID = :lotId
        "), [
            'lotId' => $lotId
        ]);

        return response()->json($result);
    }

}
?>
