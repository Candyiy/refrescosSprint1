@extends('layouts.app')
@section('title', 'Preventas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Preventas</h3>
        <a href="{{ route('preventas.create') }}" class="btn btn-primary">+ Nueva Preventa</a>
    </div>

    {{-- Búsqueda: código, cliente, fecha --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="codigo" value="{{ request('codigo') }}" class="form-control" placeholder="Buscar por código">
        </div>
        <div class="col-md-3">
            <select name="idCliente" class="form-select">
                <option value="">Todos los clientes</option>
                @foreach ($clientes as $c)
                    <option value="{{ $c->idCliente }}" @selected(request('idCliente') == $c->idCliente)>{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Preventista</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($preventas as $p)
                <tr>
                    <td>{{ $p->codigo }}</td>
                    <td>{{ $p->cliente->nombre ?? 'N/A' }}</td>
                    <td>{{ $p->preventista->nombre ?? 'N/A' }} {{ $p->preventista->apellido ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}</td>
                    <td>Bs {{ number_format($p->total, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $p->estado === 'Pendiente' ? 'warning' : ($p->estado === 'En Reparto' ? 'primary' : ($p->estado === 'Entregado' ? 'success' : 'secondary')) }}">
                            {{ $p->estado }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('preventas.show', $p->idPreventa) }}" class="btn btn-sm btn-info">Ver</a>

                        @if ($p->esModificable())
                            <a href="{{ route('preventas.edit', $p->idPreventa) }}" class="btn btn-sm btn-warning">Editar</a>

                            <form action="{{ route('preventas.cancelar', $p->idPreventa) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Cancelar esta preventa? Se liberará el stock reservado.')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-danger">Cancelar</button>
                            </form>

                            <form action="{{ route('preventas.enviarAReparto', $p->idPreventa) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Enviar a reparto? Ya no podrás editarla ni cancelarla.')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-primary">Enviar a reparto</button>
                            </form>
                        @endif

                        @if (in_array($p->estado, ['Pendiente', 'En Reparto']))
                            <form action="{{ route('preventas.entregar', $p->idPreventa) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Marcar como Entregada?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">Entregar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No hay preventas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $preventas->links() }}
@endsection