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
use App\Http\Controllers\Production\PlanningController;


/*
|--------------------------------------------------------------------------
| Authentication & Session-Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');

    /*
    |--------------------------------------------------------------------------
    | Sales Module Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('sales')->group(function () {
        Route::get('/orders', [SalesOrderController::class, 'index'])->name('sales.orders');
        // Other optional sales modules:
        // Route::get('/quotations', [QuotationController::class, 'index']);
        // Route::get('/estimates', [EstimateController::class, 'index']);
        // Route::get('/invoices', [InvoiceController::class, 'index']);
        // Route::get('/clients', [ClientController::class, 'index']);
        // Route::get('/reports', [SalesReportController::class, 'index']);
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

        Route::get('/planning', [PlanningController::class, 'index'])->name('production.planning');


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
