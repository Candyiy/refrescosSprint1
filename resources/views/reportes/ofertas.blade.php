@extends('welcome')
@section('title', 'Reporte de Ofertas')

@section('content')
<div class="container" id="reporte-content">

    <!-- Cabecera para impresión (SOLO visible al imprimir) -->
    <div class="reporte-header-print text-center mb-4" style="display: none;">
        <h2>Distribuidora de Mapocho</h2>
        <h4>Reporte de Ofertas</h4>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
        <hr>
    </div>

    <!-- ====== PARTE VISIBLE EN PANTALLA (NO SE IMPRIME) ====== -->
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Reporte de Ofertas</h3>
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
                        <h6 class="card-title">Total Ofertas</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Activas</h6>
                        <h3 class="mb-0">{{ $stats['activas'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Vencidas</h6>
                        <h3 class="mb-0">{{ $stats['vencidas'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title">Próximas</h6>
                        <h3 class="mb-0">{{ $stats['proximas'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros (SOLO PANTALLA) -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="buscar" class="form-control"
                               placeholder="Buscar por nombre" value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_desde" class="form-control"
                               value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_hasta" class="form-control"
                               value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="1" @selected(request('estado') == '1')>Activas</option>
                            <option value="0" @selected(request('estado') == '0')>Inactivas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ====== FIN PARTE VISIBLE EN PANTALLA ====== -->

    <!-- ====== TABLA DE OFERTAS (SE IMPRIME Y SE VE EN PANTALLA) ====== -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descuento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Días Vigentes</th>
                    <th>Productos</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ofertas as $o)
                    @php
                        $estadoActual = $o->estado && now()->between($o->fechaInicio, $o->fechaFin) ? 'Activa' :
                                       ($o->estado && now()->gt($o->fechaFin) ? 'Vencida' :
                                       ($o->estado && now()->lt($o->fechaInicio) ? 'Próxima' : 'Inactiva'));
                        $colorEstado = $estadoActual == 'Activa' ? 'success' :
                                      ($estadoActual == 'Vencida' ? 'danger' :
                                      ($estadoActual == 'Próxima' ? 'warning' : 'secondary'));
                    @endphp
                    <tr>
                        <td>{{ $o->nombre }}</td>
                        <td><span class="badge bg-danger">{{ $o->descuento }}%</span></td>
                        <td>{{ $o->fechaInicio->format('d/m/Y') }}</td>
                        <td>{{ $o->fechaFin->format('d/m/Y') }}</td>
                        <td>
                            @if($estadoActual == 'Activa')
                                <span class="text-success">{{ now()->diffInDays($o->fechaFin) }} días</span>
                            @elseif($estadoActual == 'Vencida')
                                <span class="text-danger">Vencida</span>
                            @elseif($estadoActual == 'Próxima')
                                <span class="text-warning">Comienza en {{ now()->diffInDays($o->fechaInicio) }} días</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $o->productos->count() }}</td>
                        <td>
                            <span class="badge bg-{{ $colorEstado }}">
                                {{ $estadoActual }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No hay ofertas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- ====== FIN TABLA ====== -->

    <!-- Pie de página para impresión -->
    <div class="reporte-footer-print text-center mt-4" style="display: none;">
        <hr>
        <p class="text-muted small">
            Reporte generado el {{ now()->format('d/m/Y H:i:s') }} - Distribuidora de Mapocho
        </p>
        <p class="text-muted small">
            Total de ofertas: {{ $ofertas->count() }}
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
        document.querySelector('.d-flex').style.display = 'flex';
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
