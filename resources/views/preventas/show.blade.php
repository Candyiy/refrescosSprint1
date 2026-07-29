@extends('layouts.app')
@section('title', 'Detalle de Preventa')

@section('content')
    <h3>Preventa {{ $preventa->codigo }}</h3>

    <div class="row mb-4">
        <div class="col-md-3"><strong>Cliente:</strong> {{ $preventa->cliente->nombre ?? 'N/A' }}</div>
        <div class="col-md-3"><strong>Preventista:</strong> {{ $preventa->preventista->nombre ?? '' }} {{ $preventa->preventista->apellido ?? '' }}</div>
        <div class="col-md-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($preventa->fecha)->format('d/m/Y') }}</div>
        <div class="col-md-3"><strong>Estado:</strong>
            <span class="badge bg-{{ $preventa->estado === 'Pendiente' ? 'warning' : ($preventa->estado === 'En Reparto' ? 'primary' : ($preventa->estado === 'Entregado' ? 'success' : 'secondary')) }}">
                {{ $preventa->estado }}
            </span>
        </div>
    </div>

    @if ($preventa->observaciones)
        <p><strong>Observaciones:</strong> {{ $preventa->observaciones }}</p>
    @endif

    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($preventa->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? 'Producto no encontrado' }}</td>
                    <td>{{ $d->cantidad }}</td>
                    <td>Bs {{ number_format($d->precio, 2) }}</td>
                    <td>Bs {{ number_format($d->subtotal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay detalles registrados para esta preventa.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-light">
                <th colspan="3" class="text-end">Total</th>
                <th>Bs {{ number_format($preventa->total, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('preventas.index') }}" class="btn btn-secondary">Volver</a>
@endsection