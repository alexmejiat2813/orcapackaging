<?php

namespace App\Models\Purchasing;

use Illuminate\Database\Eloquent\Model;

/**
 * Model that represents the view PHP_View_Jotform_Supplies_Request.
 */
class Request extends Model
{
    /**
     * The table or view associated with the model.
     *
     * @var string
     */
    protected $table = 'PHP_View_Jotform_Supplies_Request';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
?>
