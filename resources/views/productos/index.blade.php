@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>📦 Productos</h2>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            ➕ Nuevo Producto
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Presentación</th>
                    <th>Precio (Paq.)</th>
                    <th>Stock (Paq.)</th>
                    <th>Unds/Paq</th>
                    <th>Total Unidades</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>{{ $producto->presentacion ?? '-' }}</td>
                    <td>Bs {{ number_format($producto->precio, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $producto->stock <= 5 ? 'danger' : ($producto->stock <= 10 ? 'warning' : 'success') }}">
                            {{ $producto->stock }}
                        </span>
                    </td>
                    <td>{{ $producto->unidades_por_paquete }}</td>
                    <td>{{ $producto->stock * $producto->unidades_por_paquete }}</td>
                    <td>
                        <span class="badge bg-{{ $producto->estado ? 'success' : 'secondary' }}">
                            {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">✏️</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
