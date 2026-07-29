@extends('welcome')
@section('title', 'Reporte de Productos')

@section('content')
<div class="container" id="reporte-content">

    <!-- Cabecera para impresión (SOLO visible al imprimir) -->
    <div class="reporte-header-print text-center mb-4" style="display: none;">
        <h2>Distribuidora de Mapocho</h2>
        <h4>Reporte de Productos</h4>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
        <hr>
    </div>

    <!-- ====== PARTE VISIBLE EN PANTALLA (NO SE IMPRIME) ====== -->
    <div class="no-print">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Reporte de Productos</h3>
            <div>
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">← Volver</a>
                <button onclick="imprimirReporte()" class="btn btn-primary">🖨️ Imprimir</button>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Productos</h6>
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
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title">Stock Bajo (≤ 10)</h6>
                        <h3 class="mb-0">{{ $stats['stock_bajo'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Valor Inventario</h6>
                        <h5 class="mb-0">Bs {{ number_format($stats['valor_inventario'], 2) }}</h5>
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
                               placeholder="Buscar por nombre/código" value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="categoria" class="form-select">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->idCategoria }}" @selected(request('categoria') == $cat->idCategoria)>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="1" @selected(request('estado') == '1')>Activos</option>
                            <option value="0" @selected(request('estado') == '0')>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="stock_minimo" class="form-control"
                               placeholder="Stock mínimo" value="{{ request('stock_minimo') }}">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-secondary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ====== FIN PARTE VISIBLE EN PANTALLA ====== -->

    <!-- ====== TABLA DE PRODUCTOS (SE IMPRIME Y SE VE EN PANTALLA) ====== -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white" id="tabla-reporte">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Presentación</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Valor Total</th>
                    <th>Oferta</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $p)
                    @php
                        $oferta = $p->ofertaVigente();
                        $colorStock = $p->stock <= 5 ? 'danger' : ($p->stock <= 10 ? 'warning' : 'success');
                    @endphp
                    <tr>
                        <td>{{ $p->codigo }}</td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td>{{ $p->presentacion ?? '-' }}</td>
                        <td>Bs {{ number_format($p->precio, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $colorStock }}">
                                {{ $p->stock }}
                            </span>
                        </td>
                        <td>Bs {{ number_format($p->precio * $p->stock, 2) }}</td>
                        <td>
                            @if($oferta)
                                <span class="badge bg-success">
                                    🔻 {{ $oferta->descuento }}% OFF
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin oferta</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->estado ? 'success' : 'secondary' }}">
                                {{ $p->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">No hay productos registrados.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end">Totales</th>
                    <th>Bs {{ number_format($productos->sum('precio'), 2) }}</th>
                    <th>{{ $productos->sum('stock') }}</th>
                    <th>Bs {{ number_format($productos->sum(fn($p) => $p->precio * $p->stock), 2) }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- ====== FIN TABLA ====== -->

    <!-- Pie de página para impresión (SOLO visible al imprimir) -->
    <div class="reporte-footer-print text-center mt-4" style="display: none;">
        <hr>
        <p class="text-muted small">
            Reporte generado el {{ now()->format('d/m/Y H:i:s') }} - Distribuidora de Mapocho
        </p>
        <p class="text-muted small">
            Total de productos: {{ $productos->count() }}
        </p>
    </div>

    <!-- Resumen adicional (SOLO PANTALLA) -->
    <div class="row mt-4 g-3 no-print">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Resumen de Ofertas</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6>Con Oferta Vigente</h6>
                            <h3 class="text-success">{{ $stats['productos_con_oferta'] }}</h3>
                        </div>
                        <div class="col-6">
                            <h6>Sin Oferta</h6>
                            <h3 class="text-secondary">{{ $stats['total'] - $stats['productos_con_oferta'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Estado de Stock</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h6>Crítico (≤ 5)</h6>
                            <h3 class="text-danger">{{ $stats['stock_critico'] }}</h3>
                        </div>
                        <div class="col-4">
                            <h6>Bajo (≤ 10)</h6>
                            <h3 class="text-warning">{{ $stats['stock_bajo'] }}</h3>
                        </div>
                        <div class="col-4">
                            <h6>Stock Total</h6>
                            <h3 class="text-primary">{{ $stats['stock_total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function imprimirReporte() {
    // Mostrar elementos que SOLO aparecen en impresión
    document.querySelector('.reporte-header-print').style.display = 'block';
    document.querySelector('.reporte-footer-print').style.display = 'block';

    // Ocultar elementos que NO deben aparecer en impresión
    document.querySelectorAll('.no-print').forEach(el => {
        el.style.display = 'none';
    });

    // Ejecutar impresión
    window.print();

    // Restaurar después de imprimir
    setTimeout(function() {
        document.querySelector('.reporte-header-print').style.display = 'none';
        document.querySelector('.reporte-footer-print').style.display = 'none';
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = 'block';
        });
        // Restaurar flex para el contenedor de botones
        document.querySelector('.d-flex').style.display = 'flex';
    }, 500);
}
</script>

<style>
/* Ocultar en pantalla, mostrar en impresión */
.reporte-header-print, .reporte-footer-print {
    display: none !important;
}

/* Estilos para impresión */
@media print {
    /* Ocultar TODO lo que tenga clase no-print */
    .no-print {
        display: none !important;
    }

    /* Mostrar cabecera y pie de página */
    .reporte-header-print, .reporte-footer-print {
        display: block !important;
    }

    /* Resetear márgenes y fondos */
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

    /* Estilos de la tabla */
    .table {
        font-size: 11px !important;
        border-collapse: collapse !important;
        width: 100% !important;
    }

    .table th {
        background-color: #333 !important;
        color: white !important;
        padding: 5px !important;
        font-size: 11px !important;
    }

    .table td {
        padding: 4px !important;
        font-size: 11px !important;
    }

    .table-bordered {
        border: 1px solid #000 !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #000 !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9 !important;
    }

    .badge {
        border: 1px solid #000 !important;
        padding: 2px 6px !important;
        font-size: 10px !important;
    }

    .bg-success {
        background-color: #28a745 !important;
        color: white !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }

    .bg-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
        color: white !important;
    }

    .bg-primary {
        background-color: #007bff !important;
        color: white !important;
    }

    .table-dark {
        background-color: #333 !important;
        color: white !important;
    }

    .table-light {
        background-color: #f8f9fa !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* Cabecera del reporte */
    .reporte-header-print h2 {
        font-size: 18px !important;
        margin-bottom: 5px !important;
        color: #000 !important;
    }

    .reporte-header-print h4 {
        font-size: 14px !important;
        margin-bottom: 5px !important;
        color: #333 !important;
    }

    .reporte-header-print p {
        font-size: 12px !important;
        color: #666 !important;
    }

    .reporte-header-print hr {
        border-top: 2px solid #000 !important;
        width: 60% !important;
        margin: 10px auto !important;
    }

    /* Pie de página */
    .reporte-footer-print hr {
        border-top: 1px solid #000 !important;
    }

    .reporte-footer-print p {
        font-size: 10px !important;
        color: #666 !important;
        margin: 2px 0 !important;
    }
}
</style>
@endsection
