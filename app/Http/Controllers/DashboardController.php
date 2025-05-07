<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard view.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $view = view('dashboard.admin');
        return Response::noCache(response($view));
    }

    public function getInvoiceData()
    {
        //$view = view('dashboard.admin');

        // Return the dashboard view with no cache headers
        //return Response::noCache(response($view));

        
        // Optional: redirect based on role (stored in session or via Auth)
        $fonctionId = Auth::user()?->Fonction_ID;

        switch ($fonctionId) {
            case 1: // Administrative Assistant
                $data = DB::table('View_Invoice_TotalxMonth')
                ->select('year_invoice', 'month_invoice', 'subtotal', 'total')
                ->orderBy('year_invoice')
                ->orderBy('month_invoice')
                ->get();

                return response()->json($data);

            case 6: // General Worker
                return view('dashboard.journalier');

            case 9: // Press Operator
                return view('dashboard.operateur');

            default:
                return view('dashboard');
        }
        
    }

    public function getTopClientsByYear(Request $request)
{
    $year = $request->get('year');

    // Optional: redirect based on role (stored in session or via Auth)
        $fonctionId = Auth::user()?->Fonction_ID;

        switch ($fonctionId) {
            case 1: // Administrative Assistant
    $data = DB::select('WITH RankedCustomers AS (
            SELECT 
		        Customer_No,
                Customer_Name,
                DATEPART(YEAR, Invoice.Invoice_Date) AS Year,
                SUM(Invoice.SubTotal * ISNULL(Invoice.Curency_Rate, 1)) AS Total,
                RANK() OVER (PARTITION BY DATEPART(YEAR, Invoice.Invoice_Date) ORDER BY SUM(Invoice.SubTotal * ISNULL(Invoice.Curency_Rate, 1)) DESC) AS RankPerYear
            FROM ThomasOrca.dbo.Invoice
            JOIN ThomasOrca.dbo.Customer ON Customer.Customer_ID = Invoice.Customer_Id
            WHERE Invoice.Invoice_Transmit = 1
              AND DATEPART(YEAR, Invoice.Invoice_Date) BETWEEN YEAR(GETDATE()) - 4 AND YEAR(GETDATE())
            GROUP BY Customer.Customer_No,Customer.Customer_Name, DATEPART(YEAR, Invoice.Invoice_Date)
        )
        SELECT  Customer_No, Customer_Name, Year, Total
        FROM RankedCustomers
        WHERE RankPerYear <= 10 and Year = ?
        ORDER BY Year, Total DESC;', [$year]);

    $allYears = [];
    $allClients = [];

    // Index by year and client
    $matrix = [];

    foreach ($data as $row) {
        $year = $row->Year;
        $client = substr($row->Customer_Name, 0, 20);
        $safeClient = preg_replace('/[^a-zA-Z0-9]/', '_', $client); // clean label

        $allYears[$year] = true;
        $allClients[$safeClient] = true;

        $matrix[$year][$safeClient] = round($row->Total, 2);
    }

    // Build consistent structure
    $finalData = [];
    foreach (array_keys($allYears) as $year) {
        $entry = ['Year' => $year];
        foreach (array_keys($allClients) as $client) {
            $entry[$client] = $matrix[$year][$client] ?? 0;
        }
        $finalData[] = $entry;
    }

    return response()->json($finalData);
    default:
                return view('dashboard');
        }
}


}


?>
