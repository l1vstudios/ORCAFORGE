<?php

use Illuminate\Support\Facades\Route;
use Orcaforge\Http\Controllers\{
    OrcaMenuController,
    OrcaModelController,
    OrcaBaseController,
    BrainsoftController
};

Route::middleware(['web'])
    ->prefix('orcaforge')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ORCA MENU
        |--------------------------------------------------------------------------
        */
        Route::resource('menu', OrcaMenuController::class)
            ->names('orca_menu');

        Route::post('menu/columns', [OrcaMenuController::class, 'getTableColumns'])
            ->name('orca_menu.columns');

        /*
        |--------------------------------------------------------------------------
        | ORCA MODEL
        |--------------------------------------------------------------------------
        */
        Route::get('model', [OrcaModelController::class, 'index'])->name('orca_model.index');
        Route::delete('model/{model}', [OrcaModelController::class, 'destroy'])->name('orca_model.destroy');

        /*
        |--------------------------------------------------------------------------
        | ORCA BASE CONTROLLER
        |--------------------------------------------------------------------------
        */
        Route::get('controllers', [OrcaBaseController::class, 'index'])->name('orca_base.index');
        Route::delete('controllers/{controller}', [OrcaBaseController::class, 'destroy'])->name('orca_base.destroy');
        Route::prefix('database')->group(function () {
            Route::get('/', [BrainsoftController::class, 'index'])->name('brainsoft.index');
            Route::get('/{table}', [BrainsoftController::class, 'show'])->name('brainsoft.show');
            Route::post('/add-table', [BrainsoftController::class, 'addTable'])->name('brainsoft.add_table');
            Route::delete('/{table}', [BrainsoftController::class, 'deleteTable'])->name('brainsoft.delete_table');
            Route::get('/export-sql', [BrainsoftController::class, 'exportSql'])->name('brainsoft.export_sql');
            Route::post('/{table}/update-all', [BrainsoftController::class, 'updateTable'])->name('brainsoft.update_all');
            Route::post('/{table}/add-column', [BrainsoftController::class, 'addColumn'])->name('brainsoft.add_column');
            Route::delete('/{table}/{column}', [BrainsoftController::class, 'deleteColumn'])->name('brainsoft.delete_column');
            Route::post('/create-model', [BrainsoftController::class, 'createModelFromTable'])->name('brainsoft.create_model');
            Route::post('/create-fillable', [BrainsoftController::class, 'createFillableFromTable'])->name('brainsoft.create_fillable');

            Route::get('/', [BrainsoftController::class, 'index'])->name('brainsoft_database');
            Route::get('/{table}', [BrainsoftController::class, 'show'])->name('brainsoft_database.show');
            Route::post('/add-table', [BrainsoftController::class, 'addTable'])->name('brainsoft_database.add_table');
            Route::delete('/{table}', [BrainsoftController::class, 'deleteTable'])->name('brainsoft_database.delete_table');
            Route::get('/export-sql', [BrainsoftController::class, 'exportSql'])->name('brainsoft_database.export_sql');
            Route::post('/{table}/update-all', [BrainsoftController::class, 'updateTable'])->name('brainsoft_database.update_all');
            Route::post('/{table}/add-column', [BrainsoftController::class, 'addColumn'])->name('brainsoft_database.add_column');
            Route::delete('/{table}/{column}', [BrainsoftController::class, 'deleteColumn'])->name('brainsoft_database.delete_column');
            Route::post('/create-model', [BrainsoftController::class, 'createModelFromTable'])->name('brainsoft_database.create_model');
            Route::post('/create-fillable', [BrainsoftController::class, 'createFillableFromTable'])->name('brainsoft_database.create_fillable');
        });
    });
