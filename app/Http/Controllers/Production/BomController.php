<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Production\CommandeSchedule;

class BomController extends Controller
{

public function index()
    {
        $requests = CommandeSchedule::all(); // Retrieve all supply requests
        return view('production.bom', compact('requests'));
    }


    /**
     * Return BOM details for a given Lot_ID.
     */
    public function getDetails($lotid)
    {
        $sql = "
       	WITH MaterialAgrupado AS (
        SELECT c.InInvoiceNumber, l.Lot_Id, cs.Schedule_ID, p.PrNumber AS Material_Code, p.Unit_Measurement_ID,
        SUM(cr.Value) AS Estimated_Quantity, cs.Priority, cs.Scheduled_Date, cs.StatusCommande
        FROM ThomasOrca.dbo.Commande c
        INNER JOIN ThomasOrca.dbo.Commande_Receipe cr ON c.Commande_Id = cr.Commande_Id
        INNER JOIN ThomasOrca.dbo.Product p ON cr.Product_Id = p.Product_ID
        INNER JOIN ThomasOrca.dbo.Lots l ON cr.Commande_Id = l.Commande_Id
        left JOIN ThomasOrca.dbo.CommandeSchedule cs ON l.Lot_Id = cs.Lot_ID
        WHERE l.Lots_Cancel = 0 and l.Lots_Complet = 0 --cr.Actif = 1 and l.Lot_Id = 23173
        GROUP BY c.InInvoiceNumber, cs.Schedule_ID, p.PrNumber, p.Unit_Measurement_ID, cs.Priority, cs.Scheduled_Date, cs.StatusCommande, l.Lot_Id),

        StockByMaterial AS (
        SELECT p.PrNumber AS Material_Code, p.Unit_Measurement_ID,
        SUM(s.Stock_Initial_Qty - s.Stock_Qty_Used) AS Stock_Initial
        FROM ThomasOrca.dbo.Stock s
        INNER JOIN ThomasOrca.dbo.Product p ON s.Product_ID = p.Product_ID
        WHERE s.Stock_Initial_Qty - s.Stock_Qty_Used > 0
        GROUP BY p.PrNumber, p.Unit_Measurement_ID),

        InkConsumed AS (
        SELECT c.InInvoiceNumber, p.PrNumber AS Material_Code, p.Unit_Measurement_ID,
        SUM(i.qty_use_1 + i.qty_use_2 + i.qty_use_3 + i.qty_use_4 + i.qty_use_5 + i.qty_use_6 + i.qty_use_7 + i.qty_use_8 + i.qty_use_9) AS Consumed_Quantity
        FROM ThomasOrca.dbo.Commande c
        INNER JOIN ThomasOrca.dbo.Stockage stc ON c.Commande_Id = stc.commande_id
        INNER JOIN ThomasOrca.dbo.Stockage_StockageInk stink ON stc.stockage_id = stink.stockage_id
        INNER JOIN ThomasOrca.dbo.Stockage_Ink ink ON stink.stockage_ink_id = ink.stockage_ink_id
        INNER JOIN ThomasOrca.dbo.StockageInk_StockageQtyInk link ON ink.stockage_ink_id = link.stockage_ink_id
        INNER JOIN ThomasOrca.dbo.Stockage_Qty_Ink i ON i.stockage_qty_ink_id = link.stockage_qty_ink_id
        INNER JOIN ThomasOrca.dbo.Product p ON ink.type_ink = p.PrNumber
        GROUP BY c.InInvoiceNumber, p.PrNumber, p.Unit_Measurement_ID),

        PresseConsumedweight AS (
        SELECT c.InInvoiceNumber, p.PrNumber AS Material_Code, presse.unit_weight_id AS Unit_Measurement_ID,
        CASE WHEN presse.unit_weight_id = 3 THEN COUNT(presse.weight_roll) ELSE SUM(presse.weight_roll) END AS Consumed_Quantity
        FROM ThomasOrca.dbo.Commande c
        INNER JOIN ThomasOrca.dbo.Stockage s ON c.Commande_Id = s.commande_id
        INNER JOIN ThomasOrca.dbo.Stockage_StockagePresse link ON s.stockage_id = link.stockage_id
        INNER JOIN ThomasOrca.dbo.Stockage_Presse presse ON link.stockage_presse_id = presse.stockage_presse_id
        INNER JOIN ThomasOrca.dbo.Commande_Receipe r ON s.commande_id = r.Commande_Id
        INNER JOIN ThomasOrca.dbo.Product p ON r.Product_Id = p.Product_ID
        WHERE presse.qty_print > 0 AND p.ProductType_ID = 25
        GROUP BY c.InInvoiceNumber, p.PrNumber, presse.unit_weight_id),

        PresseConsumedprint AS (
        SELECT c.InInvoiceNumber, p.PrNumber AS Material_Code,
        CASE WHEN presse.unit_qty_print_id = 25 THEN 20 ELSE presse.unit_qty_print_id END AS Unit_Measurement_ID,
        SUM(presse.qty_print) AS Consumed_Quantity
        FROM ThomasOrca.dbo.Commande c
        INNER JOIN ThomasOrca.dbo.Stockage s ON c.Commande_Id = s.commande_id
        INNER JOIN ThomasOrca.dbo.Stockage_StockagePresse link ON s.stockage_id = link.stockage_id
        INNER JOIN ThomasOrca.dbo.Stockage_Presse presse ON link.stockage_presse_id = presse.stockage_presse_id
        INNER JOIN ThomasOrca.dbo.Commande_Receipe r ON s.commande_id = r.Commande_Id
        INNER JOIN ThomasOrca.dbo.Product p ON r.Product_Id = p.Product_ID
        WHERE presse.qty_print > 0 AND p.ProductType_ID = 25
        GROUP BY c.InInvoiceNumber, p.PrNumber, presse.unit_qty_print_id),

        SolvantConsumed AS (
        SELECT c.InInvoiceNumber, 'SolvantBlend8515' AS Material_Code, 12 AS Unit_Measurement_ID,
        SUM(st.solvant * 15) AS Consumed_Quantity
        FROM ThomasOrca.dbo.Commande c
        INNER JOIN ThomasOrca.dbo.Stockage st ON c.Commande_Id = st.commande_id
        WHERE st.solvant IS NOT NULL AND st.solvant > 0
        GROUP BY c.InInvoiceNumber),

        ConsumedByMaterial AS (
        SELECT InInvoiceNumber, Material_Code, Unit_Measurement_ID, SUM(Consumed_Quantity) AS Consumed_Quantity
        FROM (
        SELECT * FROM InkConsumed
        UNION ALL SELECT * FROM PresseConsumedweight
        UNION ALL SELECT * FROM PresseConsumedprint
        UNION ALL SELECT * FROM SolvantConsumed
        ) AS AllConsumed
        GROUP BY InInvoiceNumber, Material_Code, Unit_Measurement_ID),

        UnidadMaterial AS (
        SELECT cs.Schedule_ID, p.PrNumber AS Material_Code, p.Unit_Measurement_ID, u.PrLongUnitCode
        FROM ThomasOrca.dbo.Commande_Receipe cr
        INNER JOIN ThomasOrca.dbo.Product p ON cr.Product_Id = p.Product_ID
        INNER JOIN ThomasOrca.dbo.Lots l ON cr.Commande_Id = l.Commande_Id
        INNER JOIN ThomasOrca.dbo.CommandeSchedule cs ON l.Lot_Id = cs.Lot_ID
        LEFT JOIN ThomasOrca.dbo.Unit_Measurement u ON p.Unit_Measurement_ID = u.Unit_Measurement_ID
        GROUP BY cs.Schedule_ID, p.PrNumber, p.Unit_Measurement_ID, u.PrLongUnitCode),
    
        checkeado AS (
        SELECT MaterialCheck_ID ,Schedule_ID ,Material_Code, Material_Quantity,
        Unit_Measurement_ID, Checked, 
        SUM(Material_Quantity) OVER (PARTITION BY Material_Code, Unit_Measurement_ID ORDER BY Checked_At, Schedule_ID) as Checked_Cumulative
        FROM ThomasOrca.dbo.CommandeMaterialCheck
        ),
    
         ConsumoExtendido AS ( 
         SELECT m.InInvoiceNumber,m.Lot_Id, m.Material_Code, m.Schedule_ID, m.Scheduled_Date,
         m.Unit_Measurement_ID, m.Estimated_Quantity, ISNULL(chec.Material_Quantity, 0) AS Consumed_Checked, chec.Checked,
         SUM(CASE WHEN chec.Checked IS NULL THEN m.Estimated_Quantity ELSE 0 END)
         OVER (PARTITION BY m.Material_Code, m.Unit_Measurement_ID ORDER BY m.Scheduled_Date, m.Schedule_ID) AS Consumo_Acumulado
         FROM MaterialAgrupado m
         LEFT JOIN checkeado chec ON m.Schedule_ID = chec.Schedule_ID
         AND m.Material_Code = chec.Material_Code AND m.Unit_Measurement_ID = chec.Unit_Measurement_ID 
         ),

    
        ACHATS AS (
        SELECT PO.PO_ID, PO_NO, PrNumber, Order_Quantity, Order_Quantity_Unit_Measurement_ID FROM ThomasOrca.dbo.PO
        LEFT JOIN ThomasOrca.DBO.PO_Detail ON PO.PO_ID = PO_Detail.PO_ID
        LEFT JOIN ThomasOrca.DBO.Product ON PO_DetaiL.Product_ID = Product.Product_ID
        WHERE PO_Transmit = 1 AND PO_Completed = 0 AND PO_Cancel = 0
        )

        SELECT c.InInvoiceNumber, c.Lot_Id,
        c.Material_Code,
        c.Schedule_ID,
        c.Scheduled_Date,
        u.PrLongUnitCode,
        c.Estimated_Quantity,
        ach.Order_Quantity,
        ISNULL(cb.Consumed_Quantity, 0) AS Consumed_Quantity,
        ISNULL(s.Stock_Initial, 0) AS Stock_Initial,
        ISNULL(c.Consumo_Acumulado, 0) AS Consumo_Acumulado,
        ISNULL(s.Stock_Initial, 0) - ISNULL(c.Consumo_Acumulado, 0) as Stock_Remaining,
        ISNULL(c.Consumed_Checked, 0) AS Consumed_Checked, c.Checked
        FROM ConsumoExtendido c
        LEFT JOIN StockByMaterial s ON c.Material_Code = s.Material_Code AND c.Unit_Measurement_ID = s.Unit_Measurement_ID
        LEFT JOIN ConsumedByMaterial cb ON c.InInvoiceNumber = cb.InInvoiceNumber AND c.Material_Code = cb.Material_Code AND c.Unit_Measurement_ID = cb.Unit_Measurement_ID
        LEFT JOIN UnidadMaterial u ON c.Schedule_ID = u.Schedule_ID AND c.Material_Code = u.Material_Code AND c.Unit_Measurement_ID = u.Unit_Measurement_ID
        left JOIN ACHATS ACH ON c.Material_Code = ACH.PrNumber AND c.Unit_Measurement_ID = ach.Order_Quantity_Unit_Measurement_ID
        WHERE c.Lot_Id = :lotid
        ORDER BY c.Material_Code, c.Scheduled_Date;
        ";

        $result = DB::select($sql, ['lotid' => $lotid]);

        return response()->json($result);
    }

    public function storeDetail(Request $request)
{
    DB::table('CommandeMaterialCheck')->insert([
        'Schedule_ID' => $request->Schedule_ID,
        'Material_Code' => $request->Material_Code,
        'Material_Quantity' => $request->Material_Quantity,
        'Unit_Measurement_ID' => $request->Unit_Measurement_ID,
        'Comment' => $request->Comment,
        'Checked' => 0,
        'Checked_By' => auth()->id(),
        'Checked_At' => now()
    ]);

    return response()->json(['success' => true]);
}

public function updateDetail($id, Request $request)
{
    DB::table('CommandeMaterialCheck')->where('MaterialCheck_ID', $id)->update([
        'Material_Quantity' => $request->Material_Quantity,
        'Comment' => $request->Comment,
        'Checked' => $request->Checked ? 1 : 0,
        'Checked_By' => auth()->id(),
        'Checked_At' => now()
    ]);

    return response()->json(['success' => true]);
}

public function deleteDetail($id)
{
    DB::table('CommandeMaterialCheck')->where('MaterialCheck_ID', $id)->delete();

    return response()->json(['success' => true]);
}

}
?>
