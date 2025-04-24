<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    protected $table = 'Fonction'; // ← Nombre exacto en SQL Server
    protected $primaryKey = 'Fonction_ID';
    public $timestamps = false;

    protected $fillable = [
        'Fonction_Desc'
    ];

}
?>
