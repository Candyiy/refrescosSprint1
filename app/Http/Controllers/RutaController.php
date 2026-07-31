<?php
namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;
use App\Models\Preventa;

class RutaController extends Controller
{
    public function pedidos(Ruta $ruta)
    {
        $preventas = Preventa::with('cliente')
            ->whereIn('estado', ['Pendiente', 'En Reparto'])
            ->whereDoesntHave('rutas')
            ->orderBy('fecha', 'desc')
            ->get();

        $pedidosAsignados = $ruta->preventas()
            ->with('cliente')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('rutas.pedidos', compact(
            'ruta',
            'preventas',
            'pedidosAsignados'
        ));
    }

    public function asignarPedido(Request $request, Ruta $ruta)
    {
        $request->validate([
            'idPreventas' => 'required|array|min:1',
            'idPreventas.*' => 'exists:preventas,idPreventa',
        ], [
            'idPreventas.required' => 'Debe seleccionar al menos un pedido.',
            'idPreventas.min' => 'Debe seleccionar al menos un pedido.',
        ]);

        $preventas = Preventa::whereIn(
            'idPreventa',
            $request->idPreventas
        )->get();

        $asignados = 0;
        $yaAsignados = 0;

        foreach ($preventas as $preventa) {

            // Comprobar si ya pertenece a alguna ruta
            if ($preventa->rutas()->exists()) {

                $yaAsignados++;

                continue;
            }

            // Asignar preventa a la ruta
            $ruta->preventas()->attach(
                $preventa->idPreventa,
                [
                    'fechaAsignacion' => now()->toDateString(),
                ]
            );

            // Cambiar estado
            $preventa->update([
                'estado' => 'En Reparto'
            ]);

            $asignados++;
        }

        if ($asignados == 0) {

            return redirect()
                ->route('rutas.pedidos', $ruta)
                ->with(
                    'error',
                    'Los pedidos seleccionados ya están asignados a una ruta.'
                );
        }

        $mensaje = $asignados . ' pedido(s) asignado(s) correctamente.';

        if ($yaAsignados > 0) {

            $mensaje .= ' ' .
                $yaAsignados .
                ' pedido(s) ya estaban asignados a otra ruta.';

        }

        return redirect()
            ->route('rutas.pedidos', $ruta)
            ->with('success', $mensaje);
    }

    public function quitarPedido(Ruta $ruta, $idPreventa)
    {
        $ruta->preventas()->detach($idPreventa);

        $preventa = Preventa::find($idPreventa);

        if ($preventa) {
            $preventa->update([
                'estado' => 'Pendiente'
            ]);
        }

        return redirect()
            ->route('rutas.pedidos', $ruta)
            ->with('success', 'Pedido retirado de la ruta.');
    }

    public function index(Request $request)
    {
        $query = Ruta::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('zona')) {
            $query->where('zona', 'like', '%' . $request->zona . '%');
        }

        $rutas = $query->orderBy('nombre')->paginate(10)->withQueryString();
        return view('rutas.index', compact('rutas'));
    }

    public function create()
    {
        return view('rutas.create');
    }

    public function store(Request $request)
    {
        $data = $this->validarDatos($request);
        Ruta::create($data);

        return redirect()->route('rutas.index')->with('success', 'Ruta registrada correctamente.');
    }

    public function show(Ruta $ruta)
    {
        return view('rutas.show', compact('ruta'));
    }

    public function edit(Ruta $ruta)
    {
        return view('rutas.edit', compact('ruta'));
    }

    public function update(Request $request, Ruta $ruta)
    {
        $data = $this->validarDatos($request);
        $ruta->update($data);

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(Ruta $ruta)
    {
        $ruta->update(['estado' => false]); // baja lógica
        return redirect()->route('rutas.index')->with('success', 'Ruta dada de baja correctamente.');
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([
            'nombre' => 'required|string|max:150',
            'zona'   => 'required|string|max:150',
            'estado' => 'boolean',
        ]);
    }
}
