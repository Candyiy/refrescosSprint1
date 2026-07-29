@extends('layouts.app')
@section('title', 'Devoluciones')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Devoluciones</h3>
        <a href="{{ route('devoluciones.create') }}" class="btn btn-primary">+ Nueva Devolución</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="codigoPreventa" value="{{ request('codigoPreventa') }}" class="form-control" placeholder="Buscar por código de preventa">
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr><th>Preventa</th><th>Encargado</th><th>Fecha</th><th>Motivo</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @forelse ($devoluciones as $d)
                <tr>
                    <td>{{ $d->preventa->codigo }}</td>
                    <td>{{ $d->encargado->nombre }} {{ $d->encargado->apellido }}</td>
                    <td>{{ $d->fecha->format('d/m/Y') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($d->motivo, 40) }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('devoluciones.show', $d) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('devoluciones.edit', $d) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('devoluciones.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta devolución?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay devoluciones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $devoluciones->links() }}
@endsection
