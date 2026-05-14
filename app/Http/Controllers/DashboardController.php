<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Production\CommandeService;
use App\Services\Purchasing\PurchasingService;

class DashboardController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
        private PurchasingService $purchasingService
    ) {}

    public function index()
    {
        $fonctionId = Auth::user()?->Fonction_ID;

        $commandeStats = $this->commandeService->stats();
        $purchasingStats = $this->purchasingService->stats();

        $activeOrders   = $commandeStats['ready_prod'];
        $openPOs        = $purchasingStats['open_pos'];
        $completedTotal = $commandeStats['completed'];

        $formules = DB::table('ink_formules')->count();

        $lowStock = DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.Product')
            ->leftJoin('ThomasOrca.dbo.Stock', 'Product.Product_ID', '=', 'Stock.Product_ID')
            ->whereRaw('(Stock.Stock_Initial_Qty - Stock.Stock_Qty_Used) < Product.PrMinQty')
            ->where('Product.PrActif', 1)
            ->count();

        $completedToday = DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.Commande')
            ->leftJoin('ThomasOrca.dbo.Shipping', 'Commande.InInvoiceNumber', '=', 'Shipping.InInvoiceNumber')
            ->where('Commande.Complet', 1)
            ->whereRaw("CAST(Shipping.Shipping_Date AS DATE) = CAST(GETDATE() AS DATE)")
            ->count();

        $recentOrders = $this->commandeService->getReadyForProduction()->take(5);

        $showFinancials = in_array($fonctionId, [1, 14]);

        $view = view('dashboard.admin', compact(
            'activeOrders',
            'openPOs',
            'lowStock',
            'formules',
            'completedToday',
            'completedTotal',
            'recentOrders',
            'showFinancials'
        ));

        return Response::noCache(response($view));
    }

    public function getInvoiceData()
    {
        $fonctionId = Auth::user()?->Fonction_ID;

        if (!in_array($fonctionId, [1, 14])) {
            return view('home');
        }

        $data = DB::connection('sqlsrv')
            ->table('ThomasOrca.dbo.View_Invoice_TotalxMonth')
            ->select('year_invoice', 'month_invoice', 'subtotal', 'total')
            ->orderBy('year_invoice')
            ->orderBy('month_invoice')
            ->get();

        return response()->json($data);
    }

    public function getTopClientsByYear(Request $request)
    {
        $fonctionId = Auth::user()?->Fonction_ID;

        if (!in_array($fonctionId, [1, 14])) {
            return view('home');
        }

        $year = $request->get('year');

        $data = DB::connection('sqlsrv')->select('
            WITH RankedCustomers AS (
                SELECT
                    Customer_No,
                    Customer.CuSortKey as Customer_Name,
                    DATEPART(YEAR, Invoice.Invoice_Date) AS Year,
                    SUM(Invoice.SubTotal * ISNULL(Invoice.Curency_Rate, 1)) AS Total,
                    RANK() OVER (
                        PARTITION BY DATEPART(YEAR, Invoice.Invoice_Date)
                        ORDER BY SUM(Invoice.SubTotal * ISNULL(Invoice.Curency_Rate, 1)) DESC
                    ) AS RankPerYear
                FROM ThomasOrca.dbo.Invoice
                JOIN ThomasOrca.dbo.Customer ON Customer.Customer_ID = Invoice.Customer_Id
                WHERE Invoice.Invoice_Transmit = 1
                  AND DATEPART(YEAR, Invoice.Invoice_Date) BETWEEN YEAR(GETDATE()) - 4 AND YEAR(GETDATE())
                GROUP BY Customer.Customer_No, Customer.CuSortKey, DATEPART(YEAR, Invoice.Invoice_Date)
            )
            SELECT Customer_No, Customer_Name, Year, Total
            FROM RankedCustomers
            WHERE RankPerYear <= 10 AND Year = ?
            ORDER BY Year, Total DESC
        ', [$year]);

        $matrix = [];
        $allClients = [];

        foreach ($data as $row) {
            $client = substr($row->Customer_Name, 0, 20);
            $safeClient = preg_replace('/[^a-zA-Z0-9]/', '_', $client);
            $allClients[$safeClient] = true;
            $matrix[$row->Year][$safeClient] = round($row->Total, 2);
        }

        $finalData = [];
        foreach (array_keys($matrix) as $y) {
            $entry = ['Year' => $y];
            foreach (array_keys($allClients) as $client) {
                $entry[$client] = $matrix[$y][$client] ?? 0;
            }
            $finalData[] = $entry;
        }

        return response()->json($finalData);
    }
}
