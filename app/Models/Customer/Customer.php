<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use App\Models\Production\Commande;

class Customer extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Customer';
    protected $primaryKey = 'Customer_Id';
    public $timestamps = true;

    protected $fillable = [
        'Customer_No',
        'Customer_Name',
        'Rep_Name',
        'Cst_Active',
        'CuAddress',
        'CuAddress2',
        'CuCity',
        'CuProvince',
        'CuPostalCode',
        'CuISOCountryCode',
        'CuPhoneNumber1',
        'CuPhoneNumber2',
        'CuPhoneNumber3',
        'CuEMail',
        'CuEMail2',
        'CuEMail_Invoice',
        'CuEMail_Order',
        'CuWebAddress',
        'CuTotalPurchases',
        'CuLastPurchasesDate',
        'CuOpeningDate',
        'CuMarginLimit',
        'CuProspect',
        'Customer_Block_Credit',
        'Customer_Stop_Transactions',
        'CuComment',
        'Comment_Control',
    ];

    protected $casts = [
        'Cst_Active'                  => 'boolean',
        'CuProspect'                  => 'boolean',
        'Customer_Block_Credit'       => 'boolean',
        'Customer_Stop_Transactions'  => 'boolean',
        'CuLastPurchasesDate'         => 'datetime',
        'CuOpeningDate'               => 'datetime',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'Customer_Id', 'Customer_Id');
    }

    public function scopeActive($query)
    {
        return $query->where('Cst_Active', 1);
    }

    public static function rules($id = null)
    {
        $unique = $id ? ",Customer_Id,{$id}" : '';
        return [
            'Customer_Code'  => "required|string|max:50|unique:sqlsrv.Customer,Customer_Code{$unique}",
            'Customer_Name'  => 'required|string|max:100',
            'Customer_Email' => 'nullable|email|max:100',
            'Customer_Phone' => 'nullable|string|max:20',
        ];
    }
}
