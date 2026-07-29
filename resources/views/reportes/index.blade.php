@extends('layouts.app')
@section('title', 'Panel de Reportes')

@section('content')
<div class="container">
    <h3 class="mb-4">Panel de Reportes</h3>

    <div class="row g-4">
        <!-- Reporte de Productos -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-1 text-primary mb-3"></div>
                    <h5 class="card-title">Reporte de Productos</h5>
                    <p class="card-text text-muted">
                        Inventario, stock, valor total y productos con ofertas
                    </p>
                    <a href="{{ route('reportes.productos') }}" class="btn btn-primary">
                        Ver Reporte →
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Preventas -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-1 text-success mb-3"></div>
                    <h5 class="card-title">Reporte de Preventas</h5>
                    <p class="card-text text-muted">
                        Análisis de ventas, montos, estados y tendencias
                    </p>
                    <a href="{{ route('reportes.preventas') }}" class="btn btn-success">
                        Ver Reporte →
                    </a>
                </div>
            </div>
        </div>

        <!-- Reporte de Ofertas -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-1 text-danger mb-3"></div>
                    <h5 class="card-title">Reporte de Ofertas</h5>
                    <p class="card-text text-muted">
                        Ofertas activas, vencidas y su impacto en productos
                    </p>
                    <a href="{{ route('reportes.ofertas') }}" class="btn btn-danger">
                        Ver Reporte →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen General -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Resumen General</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h6 class="text-muted">Productos</h6>
                            <span class="display-6">{{ $resumen['total_productos'] }}</span>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Preventas</h6>
                            <span class="display-6">{{ $resumen['total_preventas'] }}</span>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Ofertas Activas</h6>
                            <span class="display-6">{{ $resumen['ofertas_activas'] }}</span>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Monto Total</h6>
                            <span class="display-6 text-success">
                                Bs {{ number_format($resumen['monto_total'], 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
