<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchasing\RequestController;
use App\Http\Controllers\HR\TimeInputController;

/*
|--------------------------------------------------------------------------
| Main Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', function () {
        return view('home');
    });

// Production Module (Landing)
Route::get('/production', function () {
    return view('home.production');
});

// Tools Page
Route::get('/tools', function () {
    return view('home.tools');
});


/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/

Route::prefix('login')->group(function () {
    // Login View
    Route::get('/login', function () {
        return view('login.login');
    });
});

Route::prefix('sales')->group(function () {
    Route::get('/orders', [SalesOrderController::class, 'index']);
    Route::get('/quotations', [QuotationController::class, 'index']);
    Route::get('/estimates', [EstimateController::class, 'index']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/reports', [SalesReportController::class, 'index']);
});


/*
|--------------------------------------------------------------------------
| Supply Purchasing Module Routes
|--------------------------------------------------------------------------
*/

Route::prefix('purchasing')->group(function () {

    // Dashboard (Purchasing landing page)
    Route::get('/index', function () {
        return view('purchasing.index');
    });

    // Supply Request view
    Route::get('/requests', [RequestController::class, 'index']);

    // API endpoint for Jotform submissions (returns JSON)
    Route::get('/requests/list', [RequestController::class, 'list']);

    // Purchasing management view
    Route::get('/purchasing', function () {
        return view('purchasing.purchasing');
    });

    // Reception view
    Route::get('/reception', function () {
        return view('purchasing.reception');
    });

    // Facture view
    Route::get('/facture', function () {
        return view('purchasing.facture');
    });

});


/*
|--------------------------------------------------------------------------
| Production Module Routes
|--------------------------------------------------------------------------
*/

Route::prefix('production')->group(function () {

    // Dashboard (Production landing page)
    Route::get('/index', function () {
        return view('production.index');
    });

    // Orders management
    Route::get('/orders', function () {
        return view('production.orders');
    });

    // Planning view
    Route::get('/planning', function () {
        return view('production.planning');
    });

    // Production times tracking
    Route::get('/times', function () {
        return view('production.times');
    });

});

Route::prefix('hr')->group(function () {
	Route::get('/clock', [TimeInputController::class, 'showForm'])->name('hr.clock.form'); // 🟢 Muestra el formulario
	Route::post('/clock-process', [TimeInputController::class, 'processClock'])->name('hr.clock.process'); // 🟢 Procesa Clock In/Out
    Route::get('/timeinput/data', [TimeInputController::class, 'getClockData'])->name('hr.timeinput.data');

});

?>
