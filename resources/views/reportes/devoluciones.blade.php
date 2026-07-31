@extends('welcome')
@section('title', 'Reporte de Devoluciones')

@section('content')
<div class="container" id="reporte-content">

    <!-- Cabecera para impresión -->
    <div class="reporte-header-print text-center mb-4" style="display: none;">
        <h2>Distribuidora de Mapocho</h2>
        <h4>Reporte de Devoluciones</h4>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
        <hr>
    </div>

    <!-- ====== PARTE VISIBLE EN PANTALLA ====== -->
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Reporte de Devoluciones</h3>
            <div>
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">← Volver</a>
                <button onclick="imprimirReporte()" class="btn btn-primary">🖨️ Imprimir</button>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Devoluciones</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Este Mes</h6>
                        <h3 class="mb-0">{{ $stats['total_mes'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title">Últimos 7 días</h6>
                        <h3 class="mb-0">{{ $stats['ultimos_7_dias'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Promedio Diario</h6>
                        <h3 class="mb-0">
                            @php
                                $dias = max(1, now()->diffInDays(\App\Models\Devolucion::min('fecha') ?? now()));
                            @endphp
                            {{ number_format($stats['total'] / $dias, 1) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="buscar" class="form-control"
                               placeholder="Buscar por código" value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_desde" class="form-control"
                               value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_hasta" class="form-control"
                               value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="idEncargado" class="form-select">
                            <option value="">Todos los encargados</option>
                            @foreach($encargados as $e)
                                <option value="{{ $e->idUsuario }}" @selected(request('idEncargado') == $e->idUsuario)>
                                    {{ $e->nombre }} {{ $e->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ====== FIN PARTE VISIBLE ====== -->

    <!-- ====== TABLA DE DEVOLUCIONES ====== -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Preventa</th>
                    <th>Cliente</th>
                    <th>Encargado</th>
                    <th>Fecha</th>
                    <th>Motivo</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($devoluciones as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $d->preventa->codigo ?? 'N/A' }}</strong>
                            @if($d->preventa)
                                <br>
                                <small class="text-muted">Total: Bs {{ number_format($d->preventa->total, 2) }}</small>
                            @endif
                        </td>
                        <td>{{ $d->preventa->cliente->nombre ?? 'N/A' }}</td>
                        <td>{{ $d->encargado->nombre ?? 'N/A' }} {{ $d->encargado->apellido ?? '' }}</td>
                        <td>{{ $d->fecha ? $d->fecha->format('d/m/Y') : '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($d->motivo, 50) }}</td>
                        <td>{{ $d->observacion ? \Illuminate\Support\Str::limit($d->observacion, 50) : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No hay devoluciones registradas.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="6" class="text-end">Total de devoluciones</th>
                    <th>{{ $devoluciones->count() }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- ====== FIN TABLA ====== -->

    <!-- Motivos más frecuentes -->
    @if($stats['devoluciones_por_motivo']->count() > 0)
    <div class="row mt-4 no-print">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Motivos más frecuentes de devolución</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($stats['devoluciones_por_motivo'] as $motivo)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $motivo->motivo }}
                                <span class="badge bg-primary rounded-pill">{{ $motivo->total }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Pie de página para impresión -->
    <div class="reporte-footer-print text-center mt-4" style="display: none;">
        <hr>
        <p class="text-muted small">
            Reporte generado el {{ now()->format('d/m/Y H:i:s') }} - Distribuidora de Mapocho
        </p>
        <p class="text-muted small">
            Total de devoluciones: {{ $devoluciones->count() }}
        </p>
    </div>
</div>

<script>
function imprimirReporte() {
    document.querySelector('.reporte-header-print').style.display = 'block';
    document.querySelector('.reporte-footer-print').style.display = 'block';
    document.querySelectorAll('.no-print').forEach(el => {
        el.style.display = 'none';
    });
    window.print();
    setTimeout(function() {
        document.querySelector('.reporte-header-print').style.display = 'none';
        document.querySelector('.reporte-footer-print').style.display = 'none';
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = 'block';
        });
    }, 500);
}
</script>

<style>
.reporte-header-print, .reporte-footer-print {
    display: none !important;
}

@media print {
    .no-print {
        display: none !important;
    }
    .reporte-header-print, .reporte-footer-print {
        display: block !important;
    }
    body {
        background: white !important;
        margin: 0 !important;
        padding: 15px !important;
    }
    .container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .table {
        font-size: 11px !important;
        border-collapse: collapse !important;
        width: 100% !important;
    }
    .table th {
        background-color: #333 !important;
        color: white !important;
        padding: 5px !important;
    }
    .table td {
        padding: 4px !important;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #000 !important;
    }
    .badge {
        border: 1px solid #000 !important;
    }
    .reporte-header-print h2 {
        font-size: 18px !important;
        color: #000 !important;
    }
    .reporte-header-print hr {
        border-top: 2px solid #000 !important;
        width: 60% !important;
    }
}
</style>
@endsection
