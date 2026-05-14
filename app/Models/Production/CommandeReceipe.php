<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class CommandeReceipe extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Commande_Receipe';
    protected $primaryKey = 'Commande_Receipe_Id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'Commande_Id',
        'Equipment_Id',
        'Product_Id',
        'Department_Id',
        'Value',
        'Actif',
        'Quotation_Receipe_Id',
        'Create_By',
        'TimeStamps',
        'Modified_By',
        'Modified_TimeStamps',
        'Cancel_By',
        'Cancel_TimeStamp',
        'Commande_Receipe_Changed',
        'Modified_Equipment_Id',
        'Modified_Product_Id'
    ];
}



