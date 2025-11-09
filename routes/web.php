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
        Route::resource('/menu', OrcaMenuController::class);
        Route::post('/menu/columns', [OrcaMenuController::class, 'getTableColumns'])->name('orcaforge_menu.columns');

        Route::get('/model', [OrcaModelController::class, 'index'])->name('orcaforge_model.index');
        Route::delete('/model/{model}', [OrcaModelController::class, 'destroy'])->name('orcaforge_model.destroy');

        Route::get('/controllers', [OrcaBaseController::class, 'index'])->name('orcaforge_base.index');
        Route::delete('/controllers/{controller}', [OrcaBaseController::class, 'destroy'])->name('orcaforge_base.destroy');

    });
