@extends('layouts.app')
@section('title', 'Rutas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Rutas</h3>
        <a href="{{ route('rutas.create') }}" class="btn btn-primary">+ Nueva Ruta</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="nombre" value="{{ request('nombre') }}" class="form-control" placeholder="Buscar por nombre">
        </div>
        <div class="col-md-4">
            <input type="text" name="zona" value="{{ request('zona') }}" class="form-control" placeholder="Buscar por zona">
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr><th>Nombre</th><th>Zona</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @forelse ($rutas as $r)
                <tr>
                    <td>{{ $r->nombre }}</td>
                    <td>{{ $r->zona }}</td>
                    <td><span class="badge bg-{{ $r->estado ? 'success' : 'secondary' }}">{{ $r->estado ? 'Activa' : 'Inactiva' }}</span></td>
                    <td class="text-nowrap">
                        <a href="{{ route('rutas.show', $r) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('rutas.edit', $r) }}" class="btn btn-sm btn-warning">Editar</a>
                        @if ($r->estado)
                            <form action="{{ route('rutas.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Dar de baja esta ruta?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Dar de baja</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No hay rutas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $rutas->links() }}
@endsection
