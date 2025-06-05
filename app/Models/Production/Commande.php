<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'view_commandes'; // nombre de la vista
    public $timestamps = false; // si no tiene columnas created_at/updated_at
 
}
?>
