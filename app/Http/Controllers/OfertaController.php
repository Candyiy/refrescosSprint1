<?php
namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Producto;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    // GET /ofertas
    public function index(Request $request)
    {
        $query = Oferta::with('productos');

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        $ofertas = $query->orderByDesc('idOferta')->paginate(10)->withQueryString();
        return view('ofertas.index', compact('ofertas'));
    }

    // GET /ofertas/create
    public function create()
    {
        $productos = Producto::where('estado', true)->orderBy('nombre')->get();
        return view('ofertas.create', compact('productos'));
    }

    // POST /ofertas  -> registrar()
    public function store(Request $request)
    {
        $data = $this->validarDatos($request);

        $oferta = Oferta::create($data + ['estado' => true]);
        $oferta->productos()->sync($request->input('productos', []));

        return redirect()->route('ofertas.index')
            ->with('success', 'Oferta registrada correctamente.');
    }

    // GET /ofertas/{oferta}
    public function show(Oferta $oferta)
    {
        $oferta->load('productos');
        return view('ofertas.show', compact('oferta'));
    }

    // GET /ofertas/{oferta}/edit
    public function edit(Oferta $oferta)
    {
        $productos = Producto::where('estado', true)->orderBy('nombre')->get();
$productosSeleccionados = $oferta->productos()->pluck('productos.idProducto')->toArray();
        return view('ofertas.edit', compact('oferta', 'productos', 'productosSeleccionados'));
    }

    // PUT/PATCH /ofertas/{oferta} -> editar()
    public function update(Request $request, Oferta $oferta)
    {
        $data = $this->validarDatos($request, $oferta->idOferta);

        $oferta->update($data);
        $oferta->productos()->sync($request->input('productos', []));

        return redirect()->route('ofertas.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    // PATCH /ofertas/{oferta}/dar-baja -> darBaja() (baja lógica, según diagrama de clases)
    public function darBaja(Oferta $oferta)
    {
        $oferta->update(['estado' => false]);

        return redirect()->route('ofertas.index')
            ->with('success', 'Oferta dada de baja correctamente.');
    }

    // PATCH /ofertas/{oferta}/reactivar
    public function reactivar(Oferta $oferta)
    {
        if (now()->toDateString() > $oferta->fechaFin) {
            return redirect()->route('ofertas.index')
                ->with('error', 'No se puede reactivar: la oferta ya venció.');
        }

        $oferta->update(['estado' => true]);

        return redirect()->route('ofertas.index')
            ->with('success', 'Oferta reactivada correctamente.');
    }

    private function validarDatos(Request $request, $idOferta = null): array
    {
        return $request->validate([
            'nombre'      => 'required|string|max:150',
            'descuento'   => 'required|numeric|min:0.01|max:100',
            'fechaInicio' => 'required|date',
            'fechaFin'    => 'required|date|after_or_equal:fechaInicio',
            'productos'   => 'required|array|min:1',
            'productos.*' => 'exists:productos,idProducto',
        ]);
    }
}
