<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommandeSchedule extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'CommandeSchedule';
    protected $primaryKey = 'Schedule_Id';
    public $timestamps = false;

public static function getScheduleWithReceipe()
    {
        return DB::table('ThomasOrca.dbo.Commande')
            ->leftJoin('ThomasOrca.dbo.Commande_Receipe', 'Commande.Commande_Id', '=', 'Commande_Receipe.Commande_Id')
            ->leftJoin('ThomasOrca.dbo.Lots', 'Commande_Receipe.Commande_Id', '=', 'Lots.Commande_Id')
            ->leftJoin('ThomasOrca.dbo.CommandeSchedule', function ($join) {
                $join->on('Lots.Lot_Id', '=', 'CommandeSchedule.Lot_ID')
                     ->on('Commande_Receipe.Equipment_Id', '=', 'CommandeSchedule.Equipment_ID');
            })
            ->leftJoin('ThomasOrca.dbo.Equipment', 'Equipment.Equipment_ID', '=', 'Commande_Receipe.Equipment_Id')
            ->leftJoin('ThomasOrca.dbo.Customer', 'Customer.Customer_ID', '=', 'Commande.Customer_Id')
            ->leftJoin('ThomasOrca.dbo.Product', 'Product.Product_ID', '=', 'Lots.Product_Id')
            ->select(
                'Commande.Commande_Id',
                'Commande.InInvoiceNumber',
                'Customer.Customer_No',
                'Customer.Customer_Name',
                'CommandeSchedule.Schedule_Id',
                'Lots.Lot_ID',
                'Product.PrNumber',
                'Product.PrDescription1',
                'Commande_Receipe.Equipment_Id',
                'Equipment.Equipment_Description',
                'CommandeSchedule.Equipment_ID AS Scheduled_Equipment_ID',
                'CommandeSchedule.Scheduled_Date',
                'Commande_Receipe.Value as qtyHeures'
            )
            ->where('Product.ProductType_ID', 1)
            ->where('Commande.Complet', 0)
            ->where('Commande.Cancel', 0)
            ->where('Commande.isReady_Production', 1)
            ->where('Lots.Lots_Complet', 0)
            ->where('Lots.Lots_Cancel', 0)
            ->whereNotNull('Commande_Receipe.Equipment_Id')
            ->orderBy('Lots.Lot_Id')
            ->orderBy('Commande_Receipe.Equipment_Id')
            ->orderBy('CommandeSchedule.Scheduled_Date')
            ->get();
    }



    public function schedules()
{
    return $this->hasMany(CommandeSchedule::class, 'Equipment_ID', 'Equipment_ID');
}


}
?>
