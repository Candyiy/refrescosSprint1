<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;

// Especificamos el método [DashboardController::class, 'index']
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');

    Route::get('/productos', [ReporteController::class, 'productos'])->name('productos');
    Route::get('/preventas', [ReporteController::class, 'preventas'])->name('preventas');
    Route::get('/ofertas', [ReporteController::class, 'ofertas'])->name('ofertas');

    Route::get('/exportar/{tipo}', [ReporteController::class, 'exportarPDF'])->name('exportar');
});

// Archivos de rutas adicionales
require __DIR__.'/preventa_oferta.php';