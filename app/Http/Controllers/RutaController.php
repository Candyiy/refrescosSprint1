<?php
namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
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
