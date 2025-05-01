<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class CommandeReceipe extends Model
{
    protected $table = 'Commande_Receipe'; // Nombre exacto de la tabla

    protected $primaryKey = 'Commande_Receipe_Id';

    public $timestamps = false; // Ya tienes campos personalizados de timestamps

    protected $fillable = [
        'Commande_Id',
        'Equipment_Id',
        'Product_Id',
        'Department_Id',
        'Value',
        'TimeStamps',
        'Create_By',
        'Modified_TimeStamps',
        'Modified_By',
        'Cancel_TimeStamp',
        'Cancel_By',
        'Actif',
        'Quotation_Receipe_Id',
        'Commande_Receipe_Changed',
        'Modified_Equipment_Id',
        'Modified_Product_Id'
    ];
}
