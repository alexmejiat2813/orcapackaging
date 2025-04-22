<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'PHP_View_Jotform_Supplies_Request'; // nombre exacto de la vista
    protected $primaryKey = 'id';       // si tienes una columna clave
    public $incrementing = false;
    public $timestamps = false;
}

?>
