<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetallePreventa;
use App\Models\Preventa;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PreventaController extends Controller
{
    // GET /preventas  (listar + buscar por código, cliente o fecha)
    public function index(Request $request)
    {
        $query = Preventa::with(['cliente', 'preventista']);

        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }
        if ($request->filled('idCliente')) {
            $query->where('idCliente', $request->idCliente);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $preventas = $query->orderByDesc('idPreventa')->paginate(10)->withQueryString();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('preventas.index', compact('preventas', 'clientes'));
    }

    // GET /preventas/create
    public function create()
    {
        $clientes = Cliente::where('estado', true)->orderBy('nombre')->get();
        $preventistas = Usuario::where('estado', true)->orderBy('nombre')->get();
        $productos = Producto::where('estado', true)->orderBy('nombre')->get();
        $codigoSugerido = $this->generarCodigo();

        return view('preventas.create', compact('clientes', 'preventistas', 'productos', 'codigoSugerido'));
    }

    // POST /preventas
    public function store(Request $request)
    {
        $data = $this->validarCabecera($request);
        $this->validarLineas($request);

        DB::transaction(function () use ($data, $request) {
            $preventa = Preventa::create([
                'codigo'         => $data['codigo'],
                'idCliente'      => $data['idCliente'],
                'idPreventista'  => $data['idPreventista'],
                'fecha'          => $data['fecha'],
                'observaciones'  => $data['observaciones'] ?? null,
                'estado'         => 'Pendiente',
                'total'          => 0,
            ]);

            $total = $this->guardarDetalles($preventa, $request->input('productos'));
            $preventa->update(['total' => $total]);
        });

        return redirect()->route('preventas.index')
            ->with('success', 'Preventa registrada correctamente.');
    }

    // GET /preventas/{preventa}
    public function show(Preventa $preventa)
    {
        $preventa->load(['cliente', 'preventista', 'detalles.producto']);
        return view('preventas.show', compact('preventa'));
    }

    // GET /preventas/{preventa}/edit
    public function edit(Preventa $preventa)
    {
        if (!$preventa->esModificable()) {
            return redirect()->route('preventas.index')
                ->with('error', 'No se puede modificar: la preventa ya fue enviada a distribución o está cancelada.');
        }

        $preventa->load('detalles.producto');
        $clientes = Cliente::where('estado', true)->orderBy('nombre')->get();
        $preventistas = Usuario::where('estado', true)->orderBy('nombre')->get();
        $productos = Producto::where('estado', true)->orderBy('nombre')->get();

        return view('preventas.edit', compact('preventa', 'clientes', 'preventistas', 'productos'));
    }

    // PUT/PATCH /preventas/{preventa}
    public function update(Request $request, Preventa $preventa)
    {
        if (!$preventa->esModificable()) {
            return redirect()->route('preventas.index')
                ->with('error', 'No se puede modificar: la preventa ya fue enviada a distribución o está cancelada.');
        }

        $data = $this->validarCabecera($request, $preventa->idPreventa);
        $this->validarLineas($request, $preventa);

        DB::transaction(function () use ($data, $request, $preventa) {
            foreach ($preventa->detalles as $detalleAnterior) {
                $detalleAnterior->producto->increment('stock', $detalleAnterior->cantidad * max(1, $detalleAnterior->producto->unidadesPorPaquete));
            }
            $preventa->detalles()->delete();

            $total = $this->guardarDetalles($preventa, $request->input('productos'));

            $preventa->update([
                'idCliente'     => $data['idCliente'],
                'idPreventista' => $data['idPreventista'],
                'fecha'         => $data['fecha'],
                'observaciones' => $data['observaciones'] ?? null,
                'total'         => $total,
            ]);
        });

        return redirect()->route('preventas.index')
            ->with('success', 'Preventa actualizada correctamente.');
    }

    // PATCH /preventas/{preventa}/cancelar
    public function cancelar(Preventa $preventa)
    {
        if (!$preventa->esCancelable()) {
            return redirect()->route('preventas.index')
                ->with('error', 'No se puede cancelar: la preventa ya fue entregada o ya está cancelada.');
        }

        DB::transaction(function () use ($preventa) {
            foreach ($preventa->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad * max(1, $detalle->producto->unidadesPorPaquete));
            }
            $preventa->update(['estado' => 'Cancelado']);
        });

        return redirect()->route('preventas.index')
            ->with('success', 'Preventa cancelada correctamente.');
    }

    // DELETE /preventas/{preventa} (eliminación física, solo si está Cancelado)
    public function destroy(Preventa $preventa)
    {
        if ($preventa->estado === 'Entregado') {
            return redirect()->route('preventas.index')
                ->with('error', 'No se puede eliminar una preventa ya entregada.');
        }

        DB::transaction(function () use ($preventa) {
            if ($preventa->estado !== 'Cancelado') {
                foreach ($preventa->detalles as $detalle) {
                    $detalle->producto->increment('stock', $detalle->cantidad * max(1, $detalle->producto->unidadesPorPaquete));
                }
            }
            $preventa->detalles()->delete();
            $preventa->delete();
        });

        return redirect()->route('preventas.index')
            ->with('success', 'Preventa eliminada correctamente.');
    }

    // PATCH /preventas/{preventa}/enviar-a-reparto
    public function enviarAReparto(Preventa $preventa)
    {
        if ($preventa->estado !== 'Pendiente') {
            return redirect()->route('preventas.index')
                ->with('error', 'Solo una preventa Pendiente puede enviarse a reparto.');
        }

        $preventa->update(['estado' => 'En Reparto']);

        return redirect()->route('preventas.index')
            ->with('success', 'Preventa enviada a reparto. Ya no puede editarse ni cancelarse.');
    }

    // PATCH /preventas/{preventa}/entregar
    public function marcarEntregado(Preventa $preventa)
    {
        if (!in_array($preventa->estado, ['Pendiente', 'En Reparto'])) {
            return redirect()->route('preventas.index')
                ->with('error', 'Solo una preventa Pendiente o En Reparto puede marcarse como Entregada.');
        }

        $preventa->update(['estado' => 'Entregado']);

        return redirect()->route('preventas.index')
            ->with('success', 'Preventa marcada como entregada.');
    }

    /* ---------------- helpers privados ---------------- */

   private function validarCabecera(Request $request, $idPreventaActual = null): array
    {
        return $request->validate([
            'codigo'        => 'required|string|max:30|unique:preventas,codigo,' . $idPreventaActual . ',idPreventa',
            'idCliente'     => 'required|exists:clientes,idCliente',
            'idPreventista' => 'required|exists:usuarios,idUsuario',
            
            // 🚚 REGLA LOGÍSTICA DE DISTRIBUIDORA:
            // Se bloquean fechas pasadas (ayer o anteriores) para no alterar cierres de caja ni stock.
            'fecha'         => 'required|date|after_or_equal:today',
            
            'observaciones' => 'nullable|string|max:500',
        ], [
            'fecha.after_or_equal' => 'Error de Negocio: No es posible registrar o modificar una preventa con fecha de ayer o pasadas, ya que los despachos y cierres de inventario de esas fechas ya fueron procesados.',
        ]);
    }

    private function validarLineas(Request $request, ?Preventa $preventaActual = null): void
    {
        $request->validate([
            'productos'              => 'required|array|min:1',
            'productos.*.idProducto' => 'required|exists:productos,idProducto',
            'productos.*.cantidad'   => 'required|integer|min:1',
        ]);

        $lineas = $request->input('productos');

        $ids = array_column($lineas, 'idProducto');
        if (count($ids) !== count(array_unique($ids))) {
            throw ValidationException::withMessages([
                'productos' => 'No se pueden registrar productos duplicados en la misma preventa.',
            ]);
        }

        foreach ($lineas as $linea) {
            $producto = Producto::findOrFail($linea['idProducto']);
            $unidadesPorPaquete = max(1, $producto->unidadesPorPaquete);
            $paquetesDisponibles = intdiv($producto->stock, $unidadesPorPaquete);

            if ($preventaActual) {
                $detallePrevio = $preventaActual->detalles->firstWhere('idProducto', $producto->idProducto);
                if ($detallePrevio) {
                    $paquetesDisponibles += $detallePrevio->cantidad;
                }
            }

            if ($linea['cantidad'] > $paquetesDisponibles) {
                throw ValidationException::withMessages([
                    'productos' => "Stock insuficiente para \"{$producto->nombre}\". Paquetes disponibles: {$paquetesDisponibles}.",
                ]);
            }
        }
    }

    private function guardarDetalles(Preventa $preventa, array $lineas): float
    {
        $total = 0;

        foreach ($lineas as $linea) {
            $producto = Producto::findOrFail($linea['idProducto']);
            $precioFinal = $producto->precioConOferta(); // precio por paquete
            $subtotal = $precioFinal * $linea['cantidad'];
            $total += $subtotal;

            DetallePreventa::create([
                'idPreventa' => $preventa->idPreventa,
                'idProducto' => $producto->idProducto,
                'cantidad'   => $linea['cantidad'], // cantidad en PAQUETES
                'precio'     => $precioFinal,
                'subtotal'   => $subtotal,
            ]);

            $unidadesADescontar = $linea['cantidad'] * max(1, $producto->unidadesPorPaquete);
            $producto->decrement('stock', $unidadesADescontar);
        }

        return $total;
    }

    private function generarCodigo(): string
    {
        $ultimo = Preventa::orderByDesc('idPreventa')->first();
        $siguiente = $ultimo ? $ultimo->idPreventa + 1 : 1;
        return 'PV-' . str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }
}
