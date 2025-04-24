<?php

namespace App\Models\HR;

use App\Models\HR\Fonction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Custom User model that extends Laravel's authentication base.
 */
class Users extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Users';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'Users_ID';

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
        'Users_Code',
        'Users_Fname',
        'Users_Name',
        'Fonction_ID',
        'Users_Pwd',
        'Users_Comment1',
        'Users_Comment2',
        'Users_Active',
        'Language_ID',
        'Users_Activity_Id',
        'Users_Cost',
        'Users_Equipement_Id',
        'Users_Time_Input',
        'Users_Unit_Id',
        'Users_Initial',
        'Users_Mail',
        'Random_Id',
        'Users_Nb_Work_Hours',
        'Employe_Activity_Id',
        'Employe_Nb_Work_Hours',
        'Employe_No_Paye',
        'Employe_Rate',
        'Employe_RFID',
        'Users_Paye',
        'Employe_Auto_Correction',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName(); // returns 'Users_ID'
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->Users_Pwd;
    }

    /**
     * Get the user's full name (optional helper).
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->Users_Fname} {$this->Users_Name}");
    }

    /**
     * One-to-Many: Get all time input records for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeInputs()
    {
        return $this->hasMany(TimeInput::class, 'Users_ID', 'Users_ID');
    }

    /**
     * Many-to-One: Get the role (Fonction) associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fonction()
    {
        return $this->belongsTo(Fonction::class, 'Fonction_ID', 'Fonction_ID');
    }
}

?>
