<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $ultimoProducto = Producto::orderBy('idProducto', 'desc')->first();
        $siguiente = $ultimoProducto ? $ultimoProducto->idProducto + 1 : 1;
        $codigoSugerido = 'PRO-' . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

        return view('productos.create', compact('categorias', 'codigoSugerido'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idCategoria' => 'required|exists:categorias,idCategoria',
            'codigo' => 'required|unique:productos,codigo|max:20',
            'nombre' => 'required|string|max:100',
            'presentacion' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0.01|max:999999.99',
            'stock' => 'required|integer|min:0|max:999999',
            'estado' => 'required|boolean',
            'unidades_por_paquete' => 'required|integer|min:1|max:1000',
            'tipo_paquete' => 'nullable|string|max:50',
        ], [
            'idCategoria.required' => 'La categoría es obligatoria.',
            'idCategoria.exists' => 'La categoría seleccionada no existe.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.min' => 'El precio debe ser mayor a 0.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.min' => 'El stock no puede ser negativo.',
            'unidades_por_paquete.required' => 'Las unidades por paquete son obligatorias.',
            'unidades_por_paquete.min' => 'Debe haber al menos 1 unidad por paquete.',
        ]);

        Producto::create($validated);

        return redirect()->route('productos.index')
            ->with('success', '✅ Producto registrado correctamente.');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'idCategoria' => 'required|exists:categorias,idCategoria',
            'codigo' => [
                'required',
                'max:20',
                Rule::unique('productos', 'codigo')->ignore($producto->idProducto, 'idProducto')
            ],
            'nombre' => 'required|string|max:100',
            'presentacion' => 'nullable|string|max:50',
            'precio' => 'required|numeric|min:0.01|max:999999.99',
            'stock' => 'required|integer|min:0|max:999999',
            'estado' => 'required|boolean',
            'unidades_por_paquete' => 'required|integer|min:1|max:1000',
            'tipo_paquete' => 'nullable|string|max:50',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', '✅ Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', '✅ Producto eliminado correctamente.');
    }
}
