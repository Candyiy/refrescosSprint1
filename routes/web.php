<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PreventaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\CamionController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\OfertaController;

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

// Redirigir la raíz al dashboard
Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registro
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('ofertas', OfertaController::class);
});

/*
|--------------------------------------------------------------------------
| PREVENTISTA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador,Preventista'])->group(function () {
    Route::resource('preventas', PreventaController::class);
    Route::resource('clientes', ClienteController::class);
});

/*
|--------------------------------------------------------------------------
| ALMACÉN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador,Encargado de Almacén'])->group(function () {
    Route::resource('productos', ProductoController::class);
});

/*
|--------------------------------------------------------------------------
| RUTAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador,Repartidor'])->group(function () {
    Route::resource('rutas', RutaController::class);
    // Rutas específicas para asignar/quitar pedidos
    Route::get('rutas/{ruta}/pedidos', [RutaController::class, 'pedidos'])->name('rutas.pedidos');
    Route::post('rutas/{ruta}/asignar-pedido', [RutaController::class, 'asignarPedido'])->name('rutas.asignarPedido');
    Route::delete('rutas/{ruta}/quitar-pedido/{idPreventa}', [RutaController::class, 'quitarPedido'])->name('rutas.quitarPedido');
});

/*
|--------------------------------------------------------------------------
| CAMIONES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador,Repartidor'])->group(function () {
    Route::resource('camiones', CamionController::class);
});

/*
|--------------------------------------------------------------------------
| DEVOLUCIONES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Administrador,Encargado de Almacén'])->group(function () {
    Route::resource('devoluciones', DevolucionController::class);
});

/*
|--------------------------------------------------------------------------
| REPORTES (TODOS LOS ROLES AUTENTICADOS)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('reportes')->name('reportes.')->group(function () {
    // Panel principal de reportes
    Route::get('/', [ReporteController::class, 'index'])->name('index');

    // Reportes existentes
    Route::get('/productos', [ReporteController::class, 'productos'])->name('productos');
    Route::get('/preventas', [ReporteController::class, 'preventas'])->name('preventas');
    Route::get('/ofertas', [ReporteController::class, 'ofertas'])->name('ofertas');

    // Nuevos reportes
    Route::get('/camiones', [ReporteController::class, 'camiones'])->name('camiones');
    Route::get('/devoluciones', [ReporteController::class, 'devoluciones'])->name('devoluciones');
    Route::get('/rutas', [ReporteController::class, 'rutas'])->name('rutas');

    // Exportar reportes (opcional)
    Route::get('/exportar/{tipo}', [ReporteController::class, 'exportarPDF'])->name('exportar');
});

/*
|--------------------------------------------------------------------------
| ARCHIVOS DE RUTAS ADICIONALES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/preventa_oferta.php';
