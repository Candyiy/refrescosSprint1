<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    return view('dashboard');

})
->middleware('auth')
->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Administrador'
])->group(function () {

    Route::resource('usuarios', UsuarioController::class);

});


/*
|--------------------------------------------------------------------------
| PREVENTISTA
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Administrador,Preventista'
])->group(function () {

    Route::resource('preventas', PreventaController::class);

});


/*
|--------------------------------------------------------------------------
| ALMACÉN
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Administrador,Encargado de Almacén'
])->group(function () {

    Route::resource('productos', ProductoController::class);

});


/*
|--------------------------------------------------------------------------
| RUTAS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Administrador,Repartidor'
])->group(function () {

    Route::resource('rutas', RutaController::class);

});


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ]);


    // Registro
    Route::get('/register', [
        AuthController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        AuthController::class,
        'register'
    ]);

});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [
    AuthController::class,
    'logout'
])
->middleware('auth')
->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    return view('dashboard');

})
->middleware('auth')
->name('dashboard');



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