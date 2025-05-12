<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{

public function index()
    {
        return view('production.tracking');
    }

    public function getKanbanData()
    {
        $results = DB::select("
            WITH BaseLots AS (
                SELECT 
                    c.Commande_Id,
                    c.InInvoiceNumber,
                    c.Customer_Code,
                    c.Customer_Name,
                    l.Lot_Id,
                    l.Product_Id,
                    p.PrDescription1,
                    p.PrDescription2,
                    p.PrDescription3,
                    p.PrNumber,
                    l.Commentaire,
                    l.Lots_Qty,
                    l.Lots_Price,
                    p.ProductType_ID,
                    c.isReady_Production,
                    c.Cancel AS Commande_Cancel,
                    c.Complet AS Commande_Complet,
                    l.Lots_Cancel,
                    l.Lots_Complet
                FROM ThomasOrca.dbo.Commande c
                JOIN ThomasOrca.dbo.Lots l ON c.Commande_Id = l.Commande_Id
                JOIN ThomasOrca.dbo.Product p ON l.Product_Id = p.Product_ID
                WHERE 
                    c.Cancel = 0 AND c.Complet = 0
                    AND l.Lots_Cancel = 0 
                    AND p.ProductType_ID = 1
                    AND c.isReady_Production = 1
            ),
            TimeLog AS (
                SELECT DISTINCT TimeInput_CmdNo
                FROM ThomasOrca.dbo.TimeInput
                WHERE Equipment_ID IS NOT NULL
            ),
            ShippingSummary AS (
                SELECT 
                    sd.Lot_ID,
                    SUM(ISNULL(sd.Shipping_Qty, 0)) AS Total_Shipped
                FROM ThomasOrca.dbo.Shipping_Detail sd
                JOIN ThomasOrca.dbo.Shipping s ON sd.Shipping_ID = s.Shipping_ID
                WHERE s.Shipping_Cancel = 0
                GROUP BY sd.Lot_ID
            ),
            AvailableStock AS (
                SELECT 
                    s.Lot_ID,
                    s.Product_ID,
                    SUM(s.Stock_Initial_Qty - s.Stock_Qty_Used) AS QtyAvailable
                FROM ThomasOrca.dbo.Stock s
                WHERE (s.Stock_Initial_Qty - s.Stock_Qty_Used) > 0
                GROUP BY s.Product_ID, s.Lot_ID
            )

            SELECT 
                b.Customer_Code,
                b.Customer_Name,
                b.InInvoiceNumber,
                b.Lot_Id,
                b.PrNumber,
                b.PrDescription1,
                b.PrDescription2,
                b.PrDescription3,
                b.Commentaire,
                b.Lots_Qty,
                ISNULL(ss.Total_Shipped, 0) AS Total_Shipped,
                ISNULL(st.QtyAvailable, 0) AS Qty_InStock,
                CASE 
                    WHEN ISNULL(st.QtyAvailable, 0) > 0 THEN 'Stock'
                    WHEN ISNULL(b.Lots_Complet, 0) = 1 THEN 'Done'
                    WHEN ISNULL(ss.Total_Shipped, 0) > 0 THEN 'Partial'
                    WHEN t.TimeInput_CmdNo IS NOT NULL THEN 'In Progress'
                    ELSE 'Backlog'
                END AS KANBAN_STATUS
            FROM BaseLots b
            LEFT JOIN TimeLog t ON b.InInvoiceNumber = t.TimeInput_CmdNo
            LEFT JOIN ShippingSummary ss ON b.Lot_Id = ss.Lot_ID
            LEFT JOIN AvailableStock st ON b.Lot_Id = st.Lot_ID AND b.Product_Id = st.Product_ID
            ORDER BY KANBAN_STATUS, b.Lot_Id;

        ");

        return response()->json($results);
    }

}
