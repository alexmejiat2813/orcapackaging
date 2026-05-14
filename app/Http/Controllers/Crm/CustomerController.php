<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        return view('crm.clients');
    }

    public function show(int $id)
    {
        $customer = Customer::findOrFail($id);

        $orders = DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.Commande as c')
            ->leftJoin('ThomasOrca.dbo.Production_Status as ps', 'ps.Production_Status_Id', '=', 'c.Production_Status_Id')
            ->where('c.Customer_Id', $id)
            ->orderByDesc('c.Commande_Id')
            ->take(20)
            ->select('c.Commande_Id', 'c.InInvoiceNumber', 'c.Commande_Due_Date', 'c.Complet', 'c.Cancel', 'ps.Production_Status_Description')
            ->get();

        return view('crm.client-show', compact('customer', 'orders'));
    }

    public function data()
    {
        $customers = Customer::select([
            'Customer_ID',
            'Customer_No',
            'Customer_Name',
            'Rep_Name',
            'Cst_Active',
            'CuCity',
            'CuProvince',
            'CuISOCountryCode',
            'CuPostalCode',
            'CuAddress',
            'CuPhoneNumber1',
            'CuPhoneNumber2',
            'CuEMail',
            'CuWebAddress',
            'CuTotalPurchases',
            'CuLastPurchasesDate',
            'CuOpeningDate',
        ])
        ->orderBy('Customer_Name')
        ->get();

        return response()->json($customers);
    }
}
