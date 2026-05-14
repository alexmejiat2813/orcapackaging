<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return view('crm.clients');
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
