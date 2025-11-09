<?php

use Illuminate\Support\Facades\Route;
use Orcaforge\Http\Controllers\{
    OrcaMenuController,
    OrcaModelController,
    OrcaBaseController,
    BrainsoftController
};

/*
|--------------------------------------------------------------------------
| ORCAFORGE PACKAGE ROUTES
|--------------------------------------------------------------------------
|
| Semua route di bawah ini otomatis memiliki prefix URL `/orcaforge`
| tanpa menambah prefix pada nama route (biar route('orca_menu.create') tetap valid).
|
| Contoh hasil:
|   GET  /orcaforge/menu           → route('orca_menu.index')
|   GET  /orcaforge/menu/create    → route('orca_menu.create')
|   POST /orcaforge/menu           → route('orca_menu.store')
|
*/

Route::middleware(['web'])
    ->prefix('orcaforge')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ORCA MENU
        |--------------------------------------------------------------------------
        */
        Route::resource('menu', OrcaMenuController::class)
            ->names('orca_menu'); // hasil: orca_menu.index, orca_menu.create, dst.

        Route::post('menu/columns', [OrcaMenuController::class, 'getTableColumns'])
            ->name('orca_menu.columns');

        /*
        |--------------------------------------------------------------------------
        | ORCA MODEL
        |--------------------------------------------------------------------------
        */
        Route::get('model', [OrcaModelController::class, 'index'])
            ->name('orca_model.index');
        Route::delete('model/{model}', [OrcaModelController::class, 'destroy'])
            ->name('orca_model.destroy');

        /*
        |--------------------------------------------------------------------------
        | ORCA BASE CONTROLLER
        |--------------------------------------------------------------------------
        */
        Route::get('controllers', [OrcaBaseController::class, 'index'])
            ->name('orca_base.index');
        Route::delete('controllers/{controller}', [OrcaBaseController::class, 'destroy'])
            ->name('orca_base.destroy');

        /*
        |--------------------------------------------------------------------------
        | BRAINSOFT DATABASE UTILITY (Opsional)
        |--------------------------------------------------------------------------
        */
        Route::get('database', [BrainsoftController::class, 'index'])
            ->name('brainsoft.index');
        Route::get('database/{table}', [BrainsoftController::class, 'show'])
            ->name('brainsoft.show');
    });
