<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommandeSchedule extends Model
{
    protected $table = 'CommandeSchedule'; // Nombre de la tabla en la base de datos

    // Método para obtener los datos con la consulta que definiste
    public static function getCommandeScheduleData()
    {
        // Usamos Query Builder para ejecutar la consulta
        $results = DB::table('ThomasOrca.dbo.CommandeSchedule')
            ->where('Checked', 1)
            ->select(
                'Schedule_ID',
                'Lot_Id',
                'Users_ID',
                'Equipment_ID',
                'Scheduled_Date',
                'StatusCommande',
                'Comment',
                'Created_At',
                'Update_At',
                'Checked',
                'Priority'
            )
            ->orderBy('Checked')
            ->orderBy('Scheduled_Date')
            ->orderBy('Lot_Id')
            ->get(); // Ejecutamos la consulta y obtenemos los resultados
        
        return $results;
    }

    public function schedules()
{
    return $this->hasMany(CommandeSchedule::class, 'Equipment_ID', 'Equipment_ID');
}


}
?>
