<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use Illuminate\Http\Request;

class CamionController extends Controller
{
    // GET /camiones -> incluye consultas por placa, conductor o estado
    public function index(Request $request)
    {
        $query = Camion::query();

        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }
        if ($request->filled('conductor')) {
            $query->where('conductor', 'like', '%' . $request->conductor . '%');
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado === 'activo' ? 1 : 0);
        }

        $camiones = $query->orderBy('placa')->paginate(10)->withQueryString();

        return view('camiones.index', compact('camiones'));
    }

    public function create()
    {
        return view('camiones.create');
    }

    public function store(Request $request)
    {
        $data = $this->validarDatos($request);
        
        // Manejar el checkbox/select de estado si no viene marcado
        $data['estado'] = $request->has('estado') ? (bool)$request->estado : true;

        Camion::create($data);

        return redirect()->route('camiones.index')->with('success', 'Camión registrado correctamente.');
    }

    public function show(Camion $camion)
    {
        return view('camiones.show', compact('camion'));
    }

    public function edit(Camion $camion)
    {
        return view('camiones.edit', compact('camion'));
    }

    public function update(Request $request, Camion $camion)
    {
        $data = $this->validarDatos($request, $camion->idCamion);

        // Si usas checkbox en el formulario y no está marcado, asigna false (0)
        $data['estado'] = $request->has('estado') ? (bool)$request->estado : false;

        $camion->update($data);

        return redirect()->route('camiones.index')->with('success', 'Camión actualizado correctamente.');
    }

    public function destroy(Camion $camion)
    {
        $camion->update(['estado' => false]); // Baja lógica
        return redirect()->route('camiones.index')->with('success', 'Camión dado de baja correctamente.');
    }

    private function validarDatos(Request $request, $idCamion = null): array
    {
        return $request->validate([
            'placa'     => 'required|string|max:20|unique:camiones,placa,' . $idCamion . ',idCamion',
            'conductor' => 'required|string|max:150',
            'telefono'  => 'nullable|string|max:20',
            'estado'    => 'nullable|boolean',
        ]);
    }
}