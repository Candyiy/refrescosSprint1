<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\Preventa;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    public function index(Request $request)
    {
        $query = Devolucion::with(['preventa.cliente', 'encargado']);

        if ($request->filled('codigoPreventa')) {
            $query->whereHas('preventa', fn ($q) => $q->where('codigo', 'like', '%' . $request->codigoPreventa . '%'));
        }

        $devoluciones = $query->orderByDesc('idDevolucion')->paginate(10)->withQueryString();

        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create()
    {
        // Solo preventas 'Entregado' con sus relaciones necesarias para el formulario
        $preventas = Preventa::with(['cliente', 'detalles.producto'])
            ->where('estado', 'Entregado')
            ->orderByDesc('idPreventa')
            ->get();

        $encargados = Usuario::where('estado', true)->orderBy('nombre')->get();

        return view('devoluciones.create', compact('preventas', 'encargados'));
    }

    public function store(Request $request)
    {
        $data = $this->validarDatos($request);
        $devolucion = Devolucion::create($data);
        $preventa = Preventa::find($devolucion->idPreventa);
        if ($preventa) {

            $detalles = $preventa->detalles;
            foreach ($detalles as $detalle) {
                $producto = Producto::find($detalle->idProducto);

                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();

}


}

}

        return redirect()->route('devoluciones.index')
            ->with('success', 'Devolución registrada correctamente.');
    }

    public function show(Devolucion $devolucion)
    {
        $devolucion->load(['preventa.cliente', 'preventa.detalles.producto', 'encargado']);

        return view('devoluciones.show', compact('devolucion'));
    }

    public function edit(Devolucion $devolucion)
    {
        $preventas = Preventa::with(['cliente', 'detalles.producto'])
            ->where('estado', 'Entregado')
            ->orderByDesc('idPreventa')
            ->get();

        $encargados = Usuario::where('estado', true)->orderBy('nombre')->get();

        return view('devoluciones.edit', compact('devolucion', 'preventas', 'encargados'));
    }

    public function update(Request $request, Devolucion $devolucion)
    {
        $data = $this->validarDatos($request);
        $devolucion->update($data);

        return redirect()->route('devoluciones.index')
            ->with('success', 'Devolución actualizada correctamente.');
    }

    public function destroy(Devolucion $devolucion)
    {
        $devolucion->delete();

        return redirect()->route('devoluciones.index')
            ->with('success', 'Devolución eliminada correctamente.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'idPreventa'   => 'required|exists:preventas,idPreventa',
            'idEncargado'  => 'required|exists:usuarios,idUsuario',
            'fecha'        => 'required|date|before_or_equal:today',
            'motivo'       => 'required|string|max:500',
            'observacion'  => 'nullable|string|max:1000',
        ], [
            'fecha.before_or_equal' => 'Error logístico: La fecha de devolución no puede ser una fecha futura.',
        ]);
    }
}
