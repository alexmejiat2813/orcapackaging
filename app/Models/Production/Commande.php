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
        // Usamos Query Builder para ejecutar la consulta
        $results = DB::table('ThomasOrca.dbo.Commande as c')
            ->join('ThomasOrca.dbo.Lots as l', 'c.Commande_Id', '=', 'l.Commande_Id')
            ->join('ThomasOrca.dbo.Product as p', 'l.Product_Id', '=', 'p.Product_ID')
            ->leftJoin('ThomasOrca.dbo.CommandeSchedule as cs', 'l.Lot_Id', '=', 'cs.Lot_ID')
            ->where('c.Cancel', 0)
            ->where('c.Complet', 0)
            ->where('l.Lots_Cancel', 0)
            ->where('p.ProductType_ID', 1)
            ->where('c.isReady_Production', 1)
            ->select(
                'cs.Checked',
                'cs.Schedule_ID',
                'cs.Scheduled_Date',
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
                'l.Lots_Complet'
            )
            ->orderBydesc('cs.Checked')
            ->orderBy('c.InInvoiceNumber')
            ->orderBy('cs.Scheduled_Date')
            ->orderBy('c.Date_Commande')
            ->orderBy('l.Lot_Id')
            ->get(); // Ejecutamos la consulta y obtenemos los resultados
        
        return $results;
    }
}
?>
