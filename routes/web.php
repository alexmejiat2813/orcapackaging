<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HR\TimeInputController;

use App\Http\Controllers\Supplier\SupplierController;

use App\Http\Controllers\Purchasing\POController;
use App\Http\Controllers\Purchasing\RequestController;
use App\Http\Controllers\Purchasing\JotformSuppliesController;
use App\Http\Controllers\Purchasing\FollowUp\PurchaseOrderFollowUpController;


use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesValidationOrderController;

use App\Http\Controllers\Production\CommandesController;
use App\Http\Controllers\Production\BomController;
use App\Http\Controllers\Production\PlanningController;
use App\Http\Controllers\Production\LiveOrdersController;
use App\Http\Controllers\Production\TrackingController;


use App\Http\Controllers\Sales\EstimateController;
use App\Http\Controllers\Sales\EstimateItemController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\Modules\General\DepartmentController;
use App\Http\Controllers\Settings\Modules\General\EquipmentController;
use App\Http\Controllers\Settings\Modules\Production\Requis\RequisController;
use App\Http\Controllers\Inventory\InkFormuleController;
use App\Http\Controllers\Inventory\InkFormuleComponentController;
use App\Http\Controllers\Inventory\ProductImageController;  
use App\Http\Controllers\Product\ProductController; 
use App\Http\Controllers\Product\ProductTypeController;

use App\Http\Middleware\CheckSoumissionID;
use App\Routes\Helpers\AutoSettingsRouter;

Route::get('/check-app-key', function () {
    return response()->json([
        'env_key' => env('APP_KEY'),
        'config_key' => config('app.key'),
    ]);
});

Route::middleware(['web', 'auth'])->group(function () {
    AutoSettingsRouter::register();
});


Route::middleware(['auth'])->prefix('purchasing/suppliers')->group(function () {
    Route::get('/', fn() => view('purchasing.supplier'));
    Route::get('/data', [SupplierController::class, 'index']);                  // Listar proveedores
    Route::get('/{supplierNo}', [SupplierController::class, 'show']);       // Ver proveedor por código
    Route::post('/', [SupplierController::class, 'store']);                 // Crear proveedor
    Route::put('/{id}', [SupplierController::class, 'update']);             // Actualizar proveedor
    Route::delete('/{id}', [SupplierController::class, 'destroy']);         // Eliminar proveedor
    Route::get('/{supplierNo}/contacts', [SupplierController::class, 'getContacts']); // Obtener contactos
});






















/*
|--------------------------------------------------------------------------
| Authentication & Session-Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/chart/', [DashboardController::class, 'getInvoiceData']);
    Route::get('/dashboard/chart/top-clients', [DashboardController::class, 'getTopClientsByYear']);


    Route::prefix('accounting')->group(function () {
        Route::get('/credit-check', fn() => view('accounting.check'));
        //Route::get('/credit-check', [SalesOrderController::class, 'index'])->name('accounting.check');
        
    });

    Route::get('/inventory/types-products', [ProductTypeController::class, 'index'])->name('inventory.types');
    Route::get('/inventory/products/type/{id}', [ProductController::class, 'showByType'])->name('products.byType');
    Route::get('/inventory/products/{id}', [ProductController::class, 'getProductsByType']);
    Route::get('/inventory/productsImage', [ProductImageController::class, 'index'])->name('inventory.productImage');
    Route::post('/inventory/productsImage', [ProductImageController::class, 'upload'])->name('productImage.upload');;


   

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    /*
    |--------------------------------------------------------------------------
    | Sales Module Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('sales')->group(function () {
        Route::get('/draft', [SalesOrderController::class, 'index'])->name('sales.draft');
        Route::get('/validation', [SalesValidationOrderController::class, 'index'])->name('sales.validation');
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
            Route::get('/getAssets/{customerId}', [EstimateController::class, 'getAssets']);
        });
        Route::prefix('estimates_item')->group(function () {
            Route::get('/', [EstimateItemController::class, 'index'])->middleware(CheckSoumissionID::class);
            Route::post('/storeItem', [EstimateItemController::class, 'storeItem']);
            Route::get('/gridData', [EstimateItemController::class, 'gridData']);
            Route::post('/modifier', [EstimateItemController::class, 'modifier']);
            Route::post('/supprimer', [EstimateItemController::class, 'supprimer']);
            Route::post('/copier', [EstimateItemController::class, 'copier']);
            Route::post('/itemReady', [EstimateItemController::class, 'itemReady']);
            Route::get('/getSession', [EstimateItemController::class, 'getSession']);
            Route::get('/modification', [EstimateItemController::class, 'modification']);
            Route::post('/ajouterCommentaire', [EstimateItemController::class, 'ajouterCommentaire']);
            Route::post('/images', [EstimateItemController::class, 'images'])->name('images.estimates.upload');
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


        Route::get('/suppliers/{supplierNo}', [SupplierController::class, 'show']);
		Route::get('/suppliers/{supplierNo}/contacts', [SupplierController::class, 'getContacts']);

        Route::get('/orders', fn() => view('purchasing.orders'));
        Route::get('/orders/data', [POController::class, 'index']);
        //Route::get('/orders/data', [POController::class, 'data']); // si necesitas otra vista

        Route::get('/followup', fn() => view('purchasing.followup'));
        Route::get('/purchase-followup', [PurchaseOrderFollowUpController::class, 'index']);
        Route::get('/follow-up/logs', [PurchaseOrderFollowUpController::class, 'logs']);
        Route::get('/follow-up/statuses', [PurchaseOrderFollowUpController::class, 'getStatuses']);
        Route::post('/follow-up/store', [PurchaseOrderFollowUpController::class, 'store']);
       

        // Other optional purchasing modules:
        // Route::get('/purchasing', fn() => view('purchasing.purchasing'));
        // Route::get('/reception', fn() => view('purchasing.reception'));
        // Route::get('/facture', fn() => view('purchasing.facture'));
    });

    // Production (public for now)
    Route::prefix('production')->group(function () {
        Route::get('/orders', [CommandesController::class, 'index']);
        Route::get('/production/get-commandes', [CommandesController::class, 'getCommandes']);
        Route::get('/production/get-schedules', [CommandesController::class, 'getCommandesWithSchedule']);
        Route::post('/orders/sync-schedule', [CommandesController::class, 'syncSchedule']);
        Route::get('/orders/today-schedule', [CommandesController::class, 'getTodayScheduledCommandes']);
        Route::post('/schedule/delete', [CommandesController::class, 'deleteScheduleWithReceipe']);


        Route::get('/bom', [BomController::class, 'index']);
        Route::get('/bom/get-commandes', [CommandesController::class, 'getCommandes']);
        Route::get('/bom/get-details/{lotId}', [BomController::class, 'getDetails']);

        Route::post('/bom/save-recipe', [BomController::class, 'saveRecipe']);
		Route::post('/bom/delete-recipe', [BomController::class, 'deleteRecipe']);

        Route::post('/bom/detail/store', [BomController::class, 'storeDetail']);
        Route::put('/bom/detail/update/{id}', [BomController::class, 'updateDetail']);
        Route::delete('/bom/detail/delete/{id}', [BomController::class, 'deleteDetail']);

        Route::get('/planning', [PlanningController::class, 'index']);
        Route::get('/planning/get-appointments', [PlanningController::class, 'getAppointments']);
        Route::post('/planning/save-appointment', [PlanningController::class, 'saveAppointment']);
        Route::post('/planning/delete-appointment', [PlanningController::class, 'deleteAppointment']);

        Route::get('/live-orders', [LiveOrdersController::class, 'index']);
        Route::get('/live-orders/data', [LiveOrdersController::class, 'getDataByDate']);

        Route::get('/tracking', [TrackingController::class, 'index']);
        Route::get('/tracking/get-commandes', [TrackingController::class, 'getCommandes']);

        Route::get('/workorders/uteco', fn() => view('production.workorders.uteco'));
        // Other optional purchasing modules:
        // Route::get('/purchasing', fn() => view('purchasing.purchasing'));
        // Route::get('/reception', fn() => view('purchasing.reception'));
        // Route::get('/facture', fn() => view('purchasing.facture'));
    });

    Route::prefix('inventory')->group(function () {
        Route::get('/formule-inks', [InkFormuleController::class, 'create'])->name('create');
        Route::post('/formule-inks/save', [InkFormuleController::class, 'store'])->name('store');
        Route::get('/formule-inks/list', [InkFormuleController::class, 'list']);
    });



    Route::prefix('ink-formulas')->name('ink-formulas.')->group(function () {
        Route::get('/', [InkFormuleController::class, 'index'])->name('index');
        Route::get('/create', [InkFormuleController::class, 'create'])->name('create');
        Route::post('/', [InkFormuleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [InkFormuleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InkFormuleController::class, 'update'])->name('update');
        Route::delete('/{id}', [InkFormuleController::class, 'destroy'])->name('destroy');
    });


    
    Route::prefix('ink-formulas/{formulaId}/components')->name('ink-components.')->group(function () {
        Route::get('/', [InkFormuleComponentController::class, 'index'])->name('index');
        Route::get('/create', [InkFormuleComponentController::class, 'create'])->name('create');
        Route::post('/', [InkFormuleComponentController::class, 'store'])->name('store');
        Route::get('/{componentId}/edit', [InkFormuleComponentController::class, 'edit'])->name('edit');
        Route::put('/{componentId}', [InkFormuleComponentController::class, 'update'])->name('update');
        Route::delete('/{componentId}', [InkFormuleComponentController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('settings')->group(function () {

        Route::get('/', [SettingsController::class, 'index'])->name('settings');
        // Opcionales si agregás secciones específicas
        Route::get('/profile', [SettingsController::class, 'profile'])->name('settings.profile');
        Route::get('/password', [SettingsController::class, 'password'])->name('settings.password');
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');

        Route::prefix('modules')->group(function () {
 
            Route::prefix('hr')->group(function () {
                Route::get('/', [RequisController::class, 'index']);
                Route::post('/', [RequisController::class, 'store']);
                Route::put('/{id}', [RequisController::class, 'update']);
                Route::delete('/{id}', [RequisController::class, 'destroy']);
            });

            Route::prefix('general')->group(function () {

                Route::get('/deparment/data', [RequisController::class, 'data']);
                Route::post('/deparment/', [RequisController::class, 'store']);
                Route::put('/deparment/{id}', [RequisController::class, 'update']);
                Route::delete('/deparment/{id}', [RequisController::class, 'destroy']);

                Route::get('/equipment/data', [RequisController::class, 'data']);

            });

            Route::prefix('production')->group(function () {

                Route::prefix('requis')->group(function () {

                    Route::get('/requis/data', [RequisController::class, 'data']);
                    Route::post('/requis/', [RequisController::class, 'store']);
                    Route::put('/requis/{id}', [RequisController::class, 'update']);
                    Route::delete('/requis/{id}', [RequisController::class, 'destroy']);

                });

            });

        });


    });

});



Route::prefix('purchasing')->group(function () {
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/requests/list', [RequestController::class, 'list']);


    // Ruta para importar manualmente todos los registros desde JotForm
    Route::get('/jotform/jotformSupplies/importAllSubmissions', [JotformSuppliesController::class, 'importAllSubmissions'])->name('jotform.importAll');
    // Ruta para recibir nuevos registros desde Webhook (POST desde JotForm)
    Route::post('/jotform/jotformSupplies/receiveWebhook', [JotformSuppliesController::class, 'receiveWebhook'])->name('jotform.receiveWebhook');
    // Ruta para actualizar estado "managed" desde UI
    Route::post('/jotform/jotformSupplies/updateManaged', [JotformSuppliesController::class, 'updateManaged'])->name('jotform.updateManaged');
    // Ruta para cargar todas las solicitudes de insumos (para frontend)
    Route::get('/jotform/jotformSupplies/list', [JotformSuppliesController::class, 'list'])->name('jotform.list');


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
