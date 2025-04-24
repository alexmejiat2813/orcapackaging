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
    protected $table = 'Fonction';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'Fonction_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
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
