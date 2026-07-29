@extends('layouts.app')
@section('title', 'Ofertas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Ofertas</h3>
        <a href="{{ route('ofertas.create') }}" class="btn btn-primary">+ Nueva Oferta</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="nombre" value="{{ request('nombre') }}" class="form-control" placeholder="Buscar por nombre">
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Descuento</th>
                <th>Vigencia</th>
                <th>Productos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ofertas as $o)
                <tr>
                    <td>{{ $o->nombre }}</td>
                    <td>{{ $o->descuento }}%</td>
                    <td>{{ $o->fechaInicio->format('d/m/Y') }} - {{ $o->fechaFin->format('d/m/Y') }}</td>
                    <td>{{ $o->productos->count() }}</td>
                    <td>
                        <span class="badge bg-{{ $o->estado ? 'success' : 'secondary' }}">
                            {{ $o->estado ? 'Activa' : 'De baja' }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('ofertas.show', $o) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('ofertas.edit', $o) }}" class="btn btn-sm btn-warning">Editar</a>

                        @if ($o->estado)
                            <form action="{{ route('ofertas.darBaja', $o) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Dar de baja esta oferta?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-danger">Dar de baja</button>
                            </form>
                        @else
                            <form action="{{ route('ofertas.reactivar', $o) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">Reactivar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No hay ofertas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $ofertas->links() }}
@endsection
