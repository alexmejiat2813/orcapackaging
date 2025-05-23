<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HR\TimeInputController;
use App\Http\Controllers\Purchasing\RequestController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Production\CommandesController;
use App\Http\Controllers\Production\BomController;
use App\Http\Controllers\Production\PlanningController;
use App\Http\Controllers\Production\TrackingController;
use App\Http\Controllers\Sales\EstimateController;
use App\Http\Controllers\Sales\EstimateItemController;

/*
|--------------------------------------------------------------------------
| Authentication & Session-Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/chart/', [DashboardController::class, 'getInvoiceData']);
    Route::get('dashboard/chart/top-clients', [DashboardController::class, 'getTopClientsByYear']);

    /*
    |--------------------------------------------------------------------------
    | Sales Module Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('sales')->group(function () {
        Route::get('/orders', [SalesOrderController::class, 'index'])->name('sales.orders');
        // Route::get('/quotations', [QuotationController::class, 'index']);
        // Route::get('/invoices', [InvoiceController::class, 'index']);
        // Route::get('/clients', [ClientController::class, 'index']);
        // Route::get('/reports', [SalesReportController::class, 'index']);
        ///////////////////////////////////////////////////////////////////
        Route::prefix('estimates')->group(function () {
            Route::get('/', [EstimateController::class, 'index'])->name('sales.estimate');
            Route::post('/gerer', [EstimateController::class, 'gerer'])->name('estimates.gerer');
            Route::post('/supprimer', [EstimateController::class, 'supprimer'])->name('estimates.supprimer');
            Route::post('/copier', [EstimateController::class, 'copier'])->name('estimates.copier');
            Route::post('/storeSoumission', [EstimateController::class, 'storeSoumission'])->name('estimates.storeSoumission');
            Route::post('/modifier', [EstimateController::class, 'modifier']);
            Route::get('/gridData', [EstimateController::class, 'gridData']);
            Route::get('/getSession', [EstimateController::class, 'getSession']);
        });
        Route::prefix('estimates_item')->group(function () {
            Route::get('/', [EstimateItemController::class, 'index']);
            Route::post('/storeItem', [EstimateItemController::class, 'storeItem']);
            Route::get('/gridData', [EstimateItemController::class, 'gridData']);
            Route::post('/modifier', [EstimateItemController::class, 'modifier']);
            Route::post('/supprimer', [EstimateItemController::class, 'supprimer']);
            Route::post('/copier', [EstimateItemController::class, 'copier']);
            Route::get('/getSession', [EstimateItemController::class, 'getSession']);
        });
        
        ///////////////////////////////////////////////////////////////////
    });

    /*
    |--------------------------------------------------------------------------
    | Purchasing Module Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('purchasing')->group(function () {
        Route::get('/index', fn() => view('purchasing.index'));

        // Other optional purchasing modules:
        // Route::get('/purchasing', fn() => view('purchasing.purchasing'));
        // Route::get('/reception', fn() => view('purchasing.reception'));
        // Route::get('/facture', fn() => view('purchasing.facture'));
    });

    // Production (public for now)
    Route::prefix('production')->group(function () {
        Route::get('/orders', [CommandesController::class, 'index']);
        Route::get('/production/get-commandes', [CommandesController::class, 'getCommandes']);
        Route::post('/orders/sync-schedule', [CommandesController::class, 'syncSchedule']);

        Route::get('/bom', [BomController::class, 'index']);
        Route::get('/bom/get-commandes', [CommandesController::class, 'getCommandes']);
        Route::get('/bom/get-details/{lotId}', [BomController::class, 'getDetails']);

        Route::post('/bom/detail/store', [BomController::class, 'storeDetail']);
        Route::put('/bom/detail/update/{id}', [BomController::class, 'updateDetail']);
        Route::delete('/bom/detail/delete/{id}', [BomController::class, 'deleteDetail']);

        Route::get('/planning', [PlanningController::class, 'index']);
        Route::get('/planning/get-appointments', [PlanningController::class, 'getAppointments']);
        Route::post('/planning/save-appointment', [PlanningController::class, 'saveAppointment']);
        Route::post('/planning/delete-appointment', [PlanningController::class, 'deleteAppointment']);

        Route::get('/tracking', [TrackingController::class, 'index']);
        Route::get('/tracking/kanban', [TrackingController::class, 'getKanbanData']);

        Route::get('/workorders/uteco', fn() => view('production.workorders.uteco'));
        // Other optional purchasing modules:
        // Route::get('/purchasing', fn() => view('purchasing.purchasing'));
        // Route::get('/reception', fn() => view('purchasing.reception'));
        // Route::get('/facture', fn() => view('purchasing.facture'));
    });

});



Route::prefix('purchasing')->group(function () {
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/requests/list', [RequestController::class, 'list']);

    // Other optional purchasing modules:
    // Route::get('/purchasing', fn() => view('purchasing.purchasing'));
    // Route::get('/reception', fn() => view('purchasing.reception'));
    // Route::get('/facture', fn() => view('purchasing.facture'));
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', fn() => view('home'));

// Tools
Route::get('/tools', fn() => view('home.tools'));



/*
|--------------------------------------------------------------------------
| Login & Logout
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'loginCustom'])
    ->name('login.custom');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| HR Clock In/Out
|--------------------------------------------------------------------------
*/
Route::prefix('hr')->group(function () {
    Route::get('/clock', [TimeInputController::class, 'showForm'])->name('hr.clock.form');
    Route::post('/clock-process', [TimeInputController::class, 'processClock'])->name('hr.clock.process');
    Route::get('/timeinput/data', [TimeInputController::class, 'getClockData'])->name('hr.timeinput.data');
});

?>
