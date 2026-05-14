<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $connection = 'sqlsrv';
    protected $table = 'Fonction';
    protected $primaryKey = 'Fonction_ID';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Fonction_Desc',
    ];
}

?>
