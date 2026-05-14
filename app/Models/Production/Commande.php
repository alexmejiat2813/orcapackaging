<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Modules\Production\ProductionStatus;
use App\Models\HR\Users;
use App\Models\Customer\Customer;

class Commande extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Commande';
    protected $primaryKey = 'Commande_Id';
    public $timestamps = true;

    protected $fillable = [
        'Commande_Number',
        'Commande_Date',
        'Commande_Due_Date',
        'Customer_Id',
        'Production_Status_Id',
        'Commande_Notes',
        'Commande_Active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'Commande_Date'     => 'datetime',
        'Commande_Due_Date' => 'datetime',
        'Commande_Active'   => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'Customer_Id', 'Customer_Id');
    }

    public function productionStatus()
    {
        return $this->belongsTo(ProductionStatus::class, 'Production_Status_Id', 'Production_Status_Id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Users::class, 'created_by', 'Users_ID');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'Users_ID');
    }

    public function scopeActive($query)
    {
        return $query->where('Commande_Active', 1);
    }

    public function scopeByStatus($query, $statusId)
    {
        return $query->where('Production_Status_Id', $statusId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('Customer_Id', $customerId);
    }

    public static function rules($id = null)
    {
        $unique = $id ? ",Commande_Id,{$id}" : '';
        return [
            'Commande_Number'      => "required|string|max:50|unique:sqlsrv.Commande,Commande_Number{$unique}",
            'Commande_Date'        => 'required|date',
            'Customer_Id'          => 'required|integer',
            'Production_Status_Id' => 'nullable|integer',
            'Commande_Active'      => 'boolean',
        ];
    }
}
