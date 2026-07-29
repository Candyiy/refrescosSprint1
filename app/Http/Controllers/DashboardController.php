<?php

namespace App\Http\Controllers;

use App\Models\Preventa;
use App\Models\Camion;
use App\Models\Producto;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today()->format('Y-m-d');

        // 1. VENTAS DE HOY (Busca por fecha de la preventa o fecha de creación)
        $totalHoy = Preventa::where(function($query) use ($hoy) {
            $query->whereDate('fecha', $hoy)
                  ->orWhereDate('created_at', $hoy);
        })->sum('total');

        $cantPreventasHoy = Preventa::where(function($query) use ($hoy) {
            $query->whereDate('fecha', $hoy)
                  ->orWhereDate('created_at', $hoy);
        })->count();

        // 2. POR ENVIAR A REPARTO (Contamos 'Pendiente' y 'En Reparto' si aplica)
        $pendientesReparto = Preventa::whereRaw("LOWER(TRIM(estado)) = 'pendiente'")->count();

        // 3. FLOTA ACTIVA
        $totalCamiones = Camion::count();
        $camionesActivos = Camion::where('estado', 1)->count() ?: $totalCamiones;

        // 4. ALERTAS DE STOCK BAJO (Busca productos con menos de 10 unidades)
        $productosStockBajo = Producto::where('stock', '<=', 10)->where('estado', 1)->count();
        $productosCriticos = Producto::where('stock', '<=', 10)
            ->where('estado', 1)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // 5. ÚLTIMAS PREVENTAS
        $ultimasPreventas = Preventa::with(['cliente', 'preventista'])
            ->orderByDesc('idPreventa')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalHoy',
            'cantPreventasHoy',
            'pendientesReparto',
            'totalCamiones',
            'camionesActivos',
            'productosStockBajo',
            'productosCriticos',
            'ultimasPreventas'
        ));
    }
}