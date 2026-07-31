<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Preventa;
use App\Models\Oferta;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Camion;
use App\Models\Devolucion;
use App\Models\Ruta;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // Obtener datos para el resumen general
        $resumen = [
            'total_productos' => Producto::count(),
            'total_preventas' => Preventa::count(),
            'total_ofertas' => Oferta::count(),
            'ofertas_activas' => Oferta::where('estado', true)
                ->whereDate('fechaInicio', '<=', now())
                ->whereDate('fechaFin', '>=', now())
                ->count(),
            'monto_total' => Preventa::sum('total'),
            'total_camiones' => Camion::count(),
            'total_devoluciones' => Devolucion::count(),
            'total_rutas' => Ruta::count(),
        ];

        return view('reportes.index', compact('resumen'));
    }

    /**
     * Reporte de Productos
     * GET /reportes/productos
     */
    public function productos(Request $request)
    {
        // 1. CONSTRUIR LA CONSULTA BASE
        $query = Producto::with(['categoria', 'ofertas']);

        // 2. APLICAR FILTROS
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('categoria')) {
            $query->where('idCategoria', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('stock_minimo')) {
            $query->where('stock', '<=', $request->stock_minimo);
        }

        // 3. EJECUTAR LA CONSULTA
        $productos = $query->orderBy('nombre')->get();

        // 4. CALCULAR ESTADÍSTICAS
        $stats = [
            'total' => Producto::count(),
            'activos' => Producto::where('estado', true)->count(),
            'inactivos' => Producto::where('estado', false)->count(),
            'stock_total' => Producto::sum('stock'),
            'stock_bajo' => Producto::where('stock', '<=', 10)->count(),
            'stock_critico' => Producto::where('stock', '<=', 5)->count(),
            'valor_inventario' => Producto::sum(DB::raw('precio * stock')),
            'productos_con_oferta' => Producto::whereHas('ofertas', function($q) {
                $q->where('estado', true)
                  ->whereDate('fechaInicio', '<=', now())
                  ->whereDate('fechaFin', '>=', now());
            })->count(),
            'precio_promedio' => Producto::avg('precio'),
        ];

        // 5. OBTENER DATOS PARA FILTROS
        $categorias = Categoria::orderBy('nombre')->get();

        // 6. RETORNAR VISTA CON DATOS
        return view('reportes.productos', compact('productos', 'stats', 'categorias'));
    }

    /**
     * Reporte de Preventas
     * GET /reportes/preventas
     */
    public function preventas(Request $request)
    {
        // 1. CONSTRUIR LA CONSULTA BASE
        $query = Preventa::with(['cliente', 'preventista', 'detalles.producto']);

        // 2. APLICAR FILTROS
        if ($request->filled('buscar')) {
            $query->where('codigo', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('idCliente')) {
            $query->where('idCliente', $request->idCliente);
        }

        // 3. EJECUTAR LA CONSULTA
        $preventas = $query->orderByDesc('fecha')->get();

        // 4. CALCULAR ESTADÍSTICAS
        $stats = [
            'total' => Preventa::count(),
            'total_pendientes' => Preventa::where('estado', 'Pendiente')->count(),
            'total_entregados' => Preventa::where('estado', 'Entregado')->count(),
            'total_cancelados' => Preventa::where('estado', 'Cancelado')->count(),
            'monto_total' => Preventa::sum('total'),
            'monto_pendientes' => Preventa::where('estado', 'Pendiente')->sum('total'),
            'monto_entregados' => Preventa::where('estado', 'Entregado')->sum('total'),
            'promedio_por_preventa' => Preventa::avg('total'),
            'preventa_maxima' => Preventa::max('total'),
            'preventa_minima' => Preventa::min('total'),
        ];

        // 5. DATOS PARA GRÁFICO DE VENTAS POR MES
        $ventas_por_mes = Preventa::select(
            DB::raw('YEAR(fecha) as año'),
            DB::raw('MONTH(fecha) as mes'),
            DB::raw('COUNT(*) as total_preventas'),
            DB::raw('SUM(total) as total_monto'),
            DB::raw('AVG(total) as promedio')
        )
        ->where('estado', 'Entregado')
        ->groupBy('año', 'mes')
        ->orderBy('año')
        ->orderBy('mes')
        ->get();

        // 6. OBTENER DATOS PARA FILTROS
        $clientes = Cliente::where('estado', true)->orderBy('nombre')->get();

        // 7. RETORNAR VISTA CON DATOS
        return view('reportes.preventas', compact('preventas', 'stats', 'clientes', 'ventas_por_mes'));
    }

    /**
     * Reporte de Ofertas
     * GET /reportes/ofertas
     */
    public function ofertas(Request $request)
    {
        // 1. CONSTRUIR LA CONSULTA BASE
        $query = Oferta::with('productos');

        // 2. APLICAR FILTROS
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fechaInicio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fechaFin', '<=', $request->fecha_hasta);
        }

        // 3. EJECUTAR LA CONSULTA
        $ofertas = $query->orderByDesc('fechaInicio')->get();

        // 4. CALCULAR ESTADÍSTICAS
        $stats = [
            'total' => Oferta::count(),
            'activas' => Oferta::where('estado', true)
                ->whereDate('fechaInicio', '<=', now())
                ->whereDate('fechaFin', '>=', now())
                ->count(),
            'inactivas' => Oferta::where('estado', false)->count(),
            'vencidas' => Oferta::where('estado', true)
                ->whereDate('fechaFin', '<', now())
                ->count(),
            'proximas' => Oferta::where('estado', true)
                ->whereDate('fechaInicio', '>', now())
                ->count(),
            'descuento_promedio' => Oferta::where('estado', true)->avg('descuento'),
            'descuento_maximo' => Oferta::where('estado', true)->max('descuento'),
            'descuento_minimo' => Oferta::where('estado', true)->min('descuento'),
            'total_productos_oferta' => DB::table('oferta_producto')
                ->whereIn('idOferta', Oferta::where('estado', true)->pluck('idOferta'))
                ->count(),
        ];

        // 5. RETORNAR VISTA CON DATOS
        return view('reportes.ofertas', compact('ofertas', 'stats'));
    }

    /**
     * Reporte de Camiones
     * GET /reportes/camiones
     */
    public function camiones(Request $request)
    {
        // 1. CONSTRUIR LA CONSULTA BASE
        $query = Camion::query();

        // 2. APLICAR FILTROS
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('placa', 'like', '%' . $request->buscar . '%')
                  ->orWhere('conductor', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 3. EJECUTAR LA CONSULTA
        $camiones = $query->orderBy('placa')->get();

        // 4. CALCULAR ESTADÍSTICAS
        $stats = [
            'total' => Camion::count(),
            'activos' => Camion::where('estado', true)->count(),
            'inactivos' => Camion::where('estado', false)->count(),
            'conductores_activos' => Camion::where('estado', true)->whereNotNull('conductor')->count(),
            'porcentaje_activos' => Camion::count() > 0 ? round((Camion::where('estado', true)->count() / Camion::count()) * 100, 2) : 0,
            'total_telefonos_registrados' => Camion::whereNotNull('telefono')->count(),
        ];

        // 5. RETORNAR VISTA CON DATOS
        return view('reportes.camiones', compact('camiones', 'stats'));
    }

    /**
     * Reporte de Devoluciones
     * GET /reportes/devoluciones
     */
    public function devoluciones(Request $request)
    {
        // 1. CONSTRUIR LA CONSULTA BASE
        $query = Devolucion::with(['preventa', 'preventa.cliente', 'encargado']);

        // 2. APLICAR FILTROS
        if ($request->filled('buscar')) {
            $query->whereHas('preventa', function($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('idEncargado')) {
            $query->where('idEncargado', $request->idEncargado);
        }

        // 3. EJECUTAR LA CONSULTA
        $devoluciones = $query->orderByDesc('fecha')->get();

        // 4. CALCULAR ESTADÍSTICAS
        $stats = [
            'total' => Devolucion::count(),
            'total_mes' => Devolucion::whereMonth('fecha', now()->month)
                                     ->whereYear('fecha', now()->year)
                                     ->count(),
            'ultimos_7_dias' => Devolucion::whereDate('fecha', '>=', now()->subDays(7))->count(),
            'devoluciones_por_motivo' => Devolucion::select('motivo', DB::raw('count(*) as total'))
                                                     ->groupBy('motivo')
                                                     ->orderBy('total', 'desc')
                                                     ->limit(5)
                                                     ->get(),
            'promedio_diario' => Devolucion::count() / max(1, Devolucion::distinct('fecha')->count()),
        ];

        // 5. OBTENER DATOS PARA FILTROS
        $encargados = Usuario::where('estado', true)->orderBy('nombre')->get();

        // 6. RETORNAR VISTA CON DATOS
        return view('reportes.devoluciones', compact('devoluciones', 'stats', 'encargados'));
    }

    /**
     * Reporte de Rutas
     * GET /reportes/rutas
     */
    public function rutas(Request $request)
    {
        // 1. CONSTRUIR LA CONSULTA BASE
        $query = Ruta::withCount('preventas');

        // 2. APLICAR FILTROS
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('zona', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('min_pedidos')) {
            $query->having('preventas_count', '>=', $request->min_pedidos);
        }

        // 3. EJECUTAR LA CONSULTA
        $rutas = $query->orderBy('nombre')->get();

        // 4. CALCULAR ESTADÍSTICAS
        $stats = [
            'total' => Ruta::count(),
            'activas' => Ruta::where('estado', true)->count(),
            'inactivas' => Ruta::where('estado', false)->count(),
            'total_pedidos_asignados' => DB::table('ruta_preventa')->count(),
            'promedio_pedidos_por_ruta' => Ruta::withCount('preventas')->get()->avg('preventas_count') ?? 0,
            'ruta_mas_pedidos' => Ruta::withCount('preventas')
                                      ->orderBy('preventas_count', 'desc')
                                      ->first(),
            'zonas_unicas' => Ruta::distinct('zona')->count('zona'),
            'rutas_sin_pedidos' => Ruta::withCount('preventas')
                                        ->having('preventas_count', 0)
                                        ->count(),
        ];

        // 5. OBTENER DATOS PARA GRÁFICO
        $rutas_con_pedidos = Ruta::withCount('preventas')
                                 ->where('estado', true)
                                 ->orderBy('preventas_count', 'desc')
                                 ->limit(10)
                                 ->get();

        // 6. RETORNAR VISTA CON DATOS
        return view('reportes.rutas', compact('rutas', 'stats', 'rutas_con_pedidos'));
    }

    /**
     * Exportar reporte a PDF (opcional - para futura implementación)
     */
    public function exportarPDF($tipo, Request $request)
    {
        // Esta función se puede implementar después con DOMPDF
        return redirect()->back()->with('info', 'Funcionalidad en desarrollo');
    }
}
