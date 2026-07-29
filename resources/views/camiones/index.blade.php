@extends('layouts.app')
@section('title', 'Camiones')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Camiones</h3>
        <a href="{{ route('camiones.create') }}" class="btn btn-primary">+ Nuevo Camión</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="placa" value="{{ request('placa') }}" class="form-control" placeholder="Buscar por placa">
        </div>
        <div class="col-md-3">
            <input type="text" name="conductor" value="{{ request('conductor') }}" class="form-control" placeholder="Buscar por conductor">
        </div>
        <div class="col-md-3">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="activo" @selected(request('estado')=='activo')>Activo</option>
                <option value="inactivo" @selected(request('estado')=='inactivo')>Inactivo</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr><th>Placa</th><th>Conductor</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @forelse ($camiones as $c)
                <tr>
                    <td>{{ $c->placa }}</td>
                    <td>{{ $c->conductor }}</td>
                    <td>{{ $c->telefono ?? '-' }}</td>
                    <td><span class="badge bg-{{ $c->estado ? 'success' : 'secondary' }}">{{ $c->estado ? 'Activo' : 'Inactivo' }}</span></td>
                    <td class="text-nowrap">
                        <a href="{{ route('camiones.show', $c) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('camiones.edit', $c) }}" class="btn btn-sm btn-warning">Editar</a>
                        @if ($c->estado)
                            <form action="{{ route('camiones.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Dar de baja este camión?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Dar de baja</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay camiones registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $camiones->links() }}
@endsection
