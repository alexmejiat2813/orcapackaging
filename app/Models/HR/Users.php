<?php

namespace App\Models\hr;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'Users'; // Nombre exacto de la tabla en SQL Server
    protected $primaryKey = 'Users_ID';
    public $timestamps = false; // Ya que no hay columnas created_at/updated_at

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
        'Employe_Auto_Correction'
    ];

    // Relación con TimeInput
    public function timeInputs()
    {
        return $this->hasMany(TimeInput::class, 'Users_ID', 'Users_ID');
    }

    // Opción extra: nombre completo
    public function getFullNameAttribute()
    {
        return trim("{$this->Users_Fname} {$this->Users_Name}");
    }
}

?>
