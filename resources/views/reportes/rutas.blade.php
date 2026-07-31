@extends('welcome')
@section('title', 'Reporte de Rutas')

@section('content')
<div class="container" id="reporte-content">

    <!-- Cabecera para impresión -->
    <div class="reporte-header-print text-center mb-4" style="display: none;">
        <h2>Distribuidora de Mapocho</h2>
        <h4>Reporte de Rutas</h4>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
        <hr>
    </div>

    <!-- ====== PARTE VISIBLE EN PANTALLA ====== -->
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Reporte de Rutas</h3>
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
                        <h6 class="card-title">Total Rutas</h6>
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
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title">Pedidos Asignados</h6>
                        <h3 class="mb-0">{{ $stats['total_pedidos_asignados'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Zonas</h6>
                        <h3 class="mb-0">{{ $stats['zonas_unicas'] }}</h3>
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
                               placeholder="Buscar por nombre o zona" value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="1" @selected(request('estado') == '1')>Activas</option>
                            <option value="0" @selected(request('estado') == '0')>Inactivas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="min_pedidos" class="form-control"
                               placeholder="Mínimo pedidos" value="{{ request('min_pedidos') }}">
                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-secondary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ====== FIN PARTE VISIBLE ====== -->

    <!-- ====== TABLA DE RUTAS ====== -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Zona</th>
                    <th>Pedidos Asignados</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rutas as $index => $r)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $r->nombre }}</strong></td>
                        <td>{{ $r->zona }}</td>
                        <td>
                            <span class="badge bg-{{ $r->preventas_count > 0 ? 'primary' : 'secondary' }}">
                                {{ $r->preventas_count }} pedidos
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $r->estado ? 'success' : 'secondary' }}">
                                {{ $r->estado ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td>{{ $r->created_at ? $r->created_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No hay rutas registradas.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total de rutas</th>
                    <th>{{ $rutas->sum('preventas_count') }}</th>
                    <th colspan="2">{{ $rutas->count() }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- ====== FIN TABLA ====== -->

    <!-- Top de rutas con más pedidos -->
    @if($rutas_con_pedidos->count() > 0)
    <div class="row mt-4 no-print">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Top 10 rutas con más pedidos</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($rutas_con_pedidos as $ruta)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $ruta->nombre }} ({{ $ruta->zona }})
                                <span class="badge bg-primary rounded-pill">{{ $ruta->preventas_count }} pedidos</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Resumen</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6>Promedio pedidos/ruta</h6>
                            <h3 class="text-primary">{{ number_format($stats['promedio_pedidos_por_ruta'], 1) }}</h3>
                        </div>
                        <div class="col-6">
                            <h6>Ruta con más pedidos</h6>
                            @if($stats['ruta_mas_pedidos'])
                                <h5 class="text-success">{{ $stats['ruta_mas_pedidos']->nombre }}</h5>
                                <small>{{ $stats['ruta_mas_pedidos']->preventas_count }} pedidos</small>
                            @else
                                <p class="text-muted">Sin datos</p>
                            @endif
                        </div>
                    </div>
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
            Total de rutas: {{ $rutas->count() }}
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
