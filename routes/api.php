<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommandeApiController;
use App\Http\Controllers\Api\PurchasingApiController;
use App\Http\Controllers\Api\RequisApiController;

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Production - Commandes
    |--------------------------------------------------------------------------
    */
    Route::prefix('commandes')->group(function () {
        Route::get('/',                          [CommandeApiController::class, 'index']);
        Route::get('/stats',                     [CommandeApiController::class, 'stats']);
        Route::get('/ready',                     [CommandeApiController::class, 'readyForProduction']);
        Route::get('/schedule/today',            [CommandeApiController::class, 'todaySchedule']);
        Route::get('/{id}',                      [CommandeApiController::class, 'show']);
        Route::get('/customer/{customerId}',     [CommandeApiController::class, 'byCustomer']);
        Route::get('/status/{statusId}',         [CommandeApiController::class, 'byStatus']);
    });

    /*
    |--------------------------------------------------------------------------
    | Purchasing
    |--------------------------------------------------------------------------
    */
    Route::prefix('purchasing')->group(function () {
        Route::get('/pos',                           [PurchasingApiController::class, 'openPOs']);
        Route::get('/pos/stats',                     [PurchasingApiController::class, 'stats']);
        Route::get('/pos/{id}',                      [PurchasingApiController::class, 'showPO']);
        Route::get('/pos/supplier/{supplierId}',     [PurchasingApiController::class, 'posBySupplier']);
        Route::get('/pos/{poId}/receivings',         [PurchasingApiController::class, 'receivingsForPO']);
        Route::get('/pos/{poId}/followup',           [PurchasingApiController::class, 'followUpForPO']);
        Route::get('/invoices/supplier/{supplierId}',[PurchasingApiController::class, 'invoicesForSupplier']);
    });

    /*
    |--------------------------------------------------------------------------
    | Production - Requis
    |--------------------------------------------------------------------------
    */
    Route::prefix('requis')->group(function () {
        Route::get('/',                      [RequisApiController::class, 'index']);
        Route::get('/flow',                  [RequisApiController::class, 'productionFlow']);
        Route::get('/completion-rules',      [RequisApiController::class, 'completionRules']);
        Route::get('/{id}',                  [RequisApiController::class, 'show']);
        Route::get('/status/{statusId}',     [RequisApiController::class, 'byStatus']);
    });

});
