@extends('layouts.app')
@section('title', 'Detalle de Devolución')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">
            Devolución #{{ $devolucion->idDevolucion }} — Preventa: {{ $devolucion->preventa?->codigo ?? 'Sin Preventa' }}
        </h3>
        <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary btn-sm">&larr; Volver</a>
    </div>

    <!-- TARJETA CON RESUMEN DE LA DEVOLUCIÓN -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Cliente:</strong> {{ $devolucion->preventa?->cliente?->nombre ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Encargado Almacén:</strong> {{ $devolucion->encargado?->nombre }} {{ $devolucion->encargado?->apellido }}</p>
                    <p class="mb-1"><strong>Fecha Devolución:</strong> {{ $devolucion->fecha ? $devolucion->fecha->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Motivo:</strong> {{ $devolucion->motivo }}</p>
                    @if ($devolucion->observacion)
                        <p class="mb-1"><strong>Observación:</strong> {{ $devolucion->observacion }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE PRODUCTOS DEVUELTOS -->
    <h5 class="fw-bold mb-3">📦 Productos de la Preventa Devuelta</h5>
    
    @if($devolucion->preventa && $devolucion->preventa->detalles->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad (Packs/Unid)</th>
                        <th class="text-end">Subtotal original</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($devolucion->preventa->detalles as $d)
                        <tr>
                            <td>{{ $d->producto?->nombre ?? 'Producto no registrado' }}</td>
                            <td class="text-center fw-bold">{{ $d->cantidad }}</td>
                            <td class="text-end">Bs {{ number_format($d->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end">Total Preventa:</td>
                        <td class="text-end text-primary">Bs {{ number_format($devolucion->preventa->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            ⚠️ No se encontraron detalles de productos porque la preventa seleccionada no tiene ítems cargados o no existe en el sistema.
        </div>
    @endif
@endsection