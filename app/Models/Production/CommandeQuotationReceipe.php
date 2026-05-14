<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class CommandeQuotationReceipe extends Model
{
    // Esta clase usa una vista en lugar de una tabla
    protected $connection = 'sqlsrv';
    protected $table = 'View_Commande_Quotation_Recipe';

    protected $primaryKey = 'Quotation_Receipe_Id'; // Asegúrate de que exista y sea único en la vista

    public $incrementing = false; // Por si el campo PK no es autoincremental
    public $timestamps = false;   // Sin manejo automático de created_at / updated_at

    // Campos disponibles en la vista
    protected $fillable = [
        'Commande_Receipe_Id',
        'Commande_Id_CR',
        'Product_Id',
        'Value',
        'Actif',
        'Quotation_Receipe_Id',
        'Quotation_Id',
        'Quotation_Receipe_Departement_Id',
        'Quotation_Receipe_Description',
        'Quotation_Receipe_Field',
        'Quotation_Receipe_Value',
        'Equipment_Id',
        'Paper_Code',
        'Paper_Width',
        'Commande_Id_QR',
        'ProductDisplay'
    ];
}
