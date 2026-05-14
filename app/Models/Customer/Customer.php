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
        'Customer_Code',
        'Customer_Name',
        'Customer_Active',
        'Customer_Contact',
        'Customer_Email',
        'Customer_Phone',
    ];

    protected $casts = [
        'Customer_Active' => 'boolean',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'Customer_Id', 'Customer_Id');
    }

    public function scopeActive($query)
    {
        return $query->where('Customer_Active', 1);
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
