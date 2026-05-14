<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use App\Models\HR\Users;
use App\Models\Customer\Customer;

class Commande extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Commande';
    protected $primaryKey = 'Commande_Id';

    const CREATED_AT = 'Creer_Date';
    const UPDATED_AT = 'Modifier_Date';

    protected $fillable = [
        'Customer_Id',
        'Customer_Code',
        'Customer_Name',
        'InInvoiceNumber',
        'Date_Commande',
        'Date_Demander',
        'Date_Expedition',
        'Commande_Status_id',
        'Status_Fabrication_Id',
        'Note',
        'Commentaire',
        'Cancel',
        'Complet',
        'Transmit',
        'isReady_Production',
        'Rep_No',
        'Rep_Name',
        'Creer_Par',
        'Modifier_Par',
        'Po_Client',
    ];

    protected $casts = [
        'Date_Commande'      => 'datetime',
        'Date_Demander'      => 'datetime',
        'Date_Expedition'    => 'datetime',
        'Creer_Date'         => 'datetime',
        'Modifier_Date'      => 'datetime',
        'Cancel'             => 'boolean',
        'Complet'            => 'boolean',
        'Transmit'           => 'boolean',
        'isReady_Production' => 'boolean',
        'Respecter_date'     => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'Customer_Id', 'Customer_Id');
    }

    public function productionStatus()
    {
        return $this->belongsTo(ProductionStatus::class, 'Commande_Status_id', 'Production_Status_Id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Users::class, 'Creer_Par', 'Users_ID');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Users::class, 'Modifier_Par', 'Users_ID');
    }

    public function scopeActive($query)
    {
        return $query->where('Cancel', 0)->where('Complet', 0);
    }

    public function scopeReady($query)
    {
        return $query->where('Cancel', 0)->where('Complet', 0)->where('isReady_Production', 1);
    }

    public function scopeByStatus($query, $statusId)
    {
        return $query->where('Commande_Status_id', $statusId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('Customer_Id', $customerId);
    }
}
