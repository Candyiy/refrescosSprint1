@extends('welcome')
@section('title', 'Reporte de Camiones')

@section('content')
<div class="container" id="reporte-content">

    <!-- Cabecera para impresión (SOLO visible al imprimir) -->
    <div class="reporte-header-print text-center mb-4" style="display: none;">
        <h2>Distribuidora de Mapocho</h2>
        <h4>Reporte de Camiones</h4>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
        <hr>
    </div>

    <!-- ====== PARTE VISIBLE EN PANTALLA (NO SE IMPRIME) ====== -->
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Reporte de Camiones</h3>
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
                        <h6 class="card-title">Total Camiones</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Activos</h6>
                        <h3 class="mb-0">{{ $stats['activos'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Inactivos</h6>
                        <h3 class="mb-0">{{ $stats['inactivos'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">% Activos</h6>
                        <h3 class="mb-0">{{ $stats['porcentaje_activos'] }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros (SOLO PANTALLA) -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control"
                               placeholder="Buscar por placa o conductor" value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="1" @selected(request('estado') == '1')>Activos</option>
                            <option value="0" @selected(request('estado') == '0')>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-secondary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ====== FIN PARTE VISIBLE EN PANTALLA ====== -->

    <!-- ====== TABLA DE CAMIONES (SE IMPRIME Y SE VE EN PANTALLA) ====== -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white" id="tabla-reporte">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Placa</th>
                    <th>Conductor</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($camiones as $index => $c)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $c->placa }}</strong></td>
                        <td>{{ $c->conductor ?? 'Sin conductor' }}</td>
                        <td>{{ $c->telefono ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $c->estado ? 'success' : 'secondary' }}">
                                {{ $c->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>{{ $c->created_at ? $c->created_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No hay camiones registrados.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="5" class="text-end">Total de camiones</th>
                    <th>{{ $camiones->count() }}</th>
                </tr>
            </tfoot>
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
            Total de camiones: {{ $camiones->count() }}
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
