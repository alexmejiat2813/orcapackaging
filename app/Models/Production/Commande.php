<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Commande extends Model
{
    protected $table = 'Commande'; // Nombre de la tabla en la base de datos

    // Método para obtener los datos con la consulta que definiste
    public static function getCommandeData()
{
    $results = DB::table('ThomasOrca.dbo.Commande as c')
        ->join('ThomasOrca.dbo.Lots as l', 'c.Commande_Id', '=', 'l.Commande_Id')
        ->join('ThomasOrca.dbo.Product as p', 'l.Product_Id', '=', 'p.Product_ID')
        ->leftJoin(DB::raw('(
            SELECT Lot_ID, MIN(Scheduled_Date) as Scheduled_Date
            FROM ThomasOrca.dbo.CommandeSchedule
            GROUP BY Lot_ID
        ) as cs'), 'cs.Lot_ID', '=', 'l.Lot_Id')
        ->where('c.Cancel', 0)
        ->where('c.Complet', 0)
        ->where('l.Lots_Cancel', 0)
         ->where('l.Lots_Complet', 0)
        ->where('p.ProductType_ID', 1)
        ->where('c.isReady_Production', 1)
        ->select(
            'c.Commande_Id',
            'c.Customer_Code',
            'c.Customer_Name',
            'c.InInvoiceNumber',
            'c.Date_Commande',
            'c.Date_Demander',
            'c.Date_Expedition',
            'c.Po_Client',
            'c.Acheteur',
            'c.Transmit',
            'c.isReady_Production',
            'l.Lot_Id',
            'l.Product_Id',
            'p.PrNumber',
            'p.PrDescription1',
            'l.Lots_Qty',
            'l.Lots_Price',
            'l.Shipping_Qty',
            'l.Commentaire',
            'l.Lots_Complet',
            'c.SubTotal',
            'c.Total',
            DB::raw('(l.Lots_Qty - l.Shipping_Qty) as Qty_Finish'),
            'cs.Scheduled_Date' // ← la fecha mínima por lote
        )
        ->orderBy('c.InInvoiceNumber')
        ->orderBy('l.Lot_Id')
        ->get();

    return $results;
}

    
}
?>
