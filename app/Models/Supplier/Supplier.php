<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchasing\PO;
use App\Models\Asset\Asset;
use App\Models\Asset\AssetLink;

class Supplier extends Model
{
    protected $table = 'Supplier';
    protected $primaryKey = 'Supplier_ID';
    public $timestamps = false;

    protected $fillable = [
        'Supplier_Name',
        'Supplier_No',
        'Supplier_CareOf',
        'Supplier_Address',
        'Supplier_Address2',
        'Supplier_City',
        'Supplier_PostalCode',
        'Supplier_PhoneNumber1',
        'Supplier_PhoneNumber2',
        'Supplier_Email',
        'Supplier_Active',
        'Supplier_SortKey',
        'Supplier_Lock',
        'Supplier_ISOCountryCode',
        'Supplier_WebAddress'
        // Añade más campos según sea necesario
    ];

    // Relaciones
    public function purchaseOrders()
    {
        return $this->hasMany(PO::class, 'Supplier_ID');
    }

    public function assetLinks()
    {
        return $this->hasMany(AssetLink::class, 'Supplier_ID', 'Supplier_ID');
    }

    public function activeContacts()
    {
        return $this->hasMany(AssetLink::class, 'Supplier_ID', 'Supplier_ID')
            ->join('Asset', 'Asset.Asset_ID', '=', 'Asset_Link.Asset_ID')
            ->where('Asset.Asset_Active', 1)
            ->select([
                'Asset.Asset_ID as Asset_ID',
                'Asset.Asset_FName',
                'Asset.Asset_Name'
            ]);
    }

    // CRUD utilitarios
    public static function allSuppliers()
    {
        return self::select(
            'Supplier_Name',
            'Supplier_No',
            'Supplier_CareOf',
            'Supplier_Address',
            'Supplier_Address2',
            'Supplier_City',
            'Supplier_PostalCode',
            'Supplier_PhoneNumber1',
            'Supplier_PhoneNumber2',
            'Supplier_Email',
            'Supplier_Active',
            'Supplier_SortKey',
            'Supplier_Lock',
            'Supplier_ISOCountryCode',
            'Supplier_WebAddress'
        )->get();
    }

    public static function findByNo($supplierNo)
    {
        return self::where('Supplier_No', $supplierNo)->first();
    }

    public static function createSupplier(array $data)
    {
        return self::create($data);
    }

    public function updateSupplier(array $data)
    {
        return $this->update($data);
    }

    public function deleteSupplier()
    {
        return $this->delete();
    }
}

