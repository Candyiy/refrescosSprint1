<?php

use App\Http\Controllers\CamionController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PreventaController;
use App\Http\Controllers\RutaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas de Preventa, Oferta, Camion, Ruta y Devolucion
|--------------------------------------------------------------------------
| Incluir en routes/web.php con:  require __DIR__.'/preventa_oferta.php';
*/

Route::resource('preventas', PreventaController::class);
Route::patch('preventas/{preventa}/cancelar', [PreventaController::class, 'cancelar'])->name('preventas.cancelar');
Route::patch('preventas/{preventa}/enviar-a-reparto', [PreventaController::class, 'enviarAReparto'])->name('preventas.enviarAReparto');
Route::patch('preventas/{preventa}/entregar', [PreventaController::class, 'marcarEntregado'])->name('preventas.entregar');

Route::resource('ofertas', OfertaController::class)->except(['destroy']);
Route::patch('ofertas/{oferta}/dar-baja', [OfertaController::class, 'darBaja'])->name('ofertas.darBaja');
Route::patch('ofertas/{oferta}/reactivar', [OfertaController::class, 'reactivar'])->name('ofertas.reactivar');

// 💡 CORRECCIÓN EN CAMIONES Y DEVOLUCIONES:
Route::resource('camiones', CamionController::class)->parameters([
    'camiones' => 'camion'
]);

Route::resource('rutas', RutaController::class);

// 💡 Mapeamos el parámetro para que Laravel no busque 'devolucione' sino 'devolucion'
Route::resource('devoluciones', DevolucionController::class)->parameters([
    'devoluciones' => 'devolucion'
]);