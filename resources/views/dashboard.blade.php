@extends('layouts.app')
@section('title', 'Panel de Control - Distribuidora')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado Principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">🥤 Panel de Control Logístico</h3>
            <p class="text-muted mb-0 small">
                Resumen operativo y monitoreo de rutas • {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM, YYYY') }}
            </p>
        </div>
        <div>
            @if(in_array(auth()->user()->rol->nombre, ['Administrador', 'Preventista']))

                <a href="{{ route('preventas.create') }}"
                class="btn btn-primary shadow-sm fw-bold">

                    <i class="bi bi-plus-lg"></i>
                    + Nueva Preventa

                </a>

            @endif
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS CLAVE (KPIs) -->
    <div class="row g-3 mb-4">
        <!-- Preventas del Día -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm border-start border-4 border-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Ventas de Hoy</div>
                            <h3 class="fw-bold text-dark my-1">Bs {{ number_format($totalHoy ?? 0, 2) }}</h3>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                {{ $cantPreventasHoy ?? 0 }} pedidos registrados
                            </span>
                        </div>
                        <div class="bg-success text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            💼
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos Pendientes/Por Despachar -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm border-start border-4 border-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Por Enviar a Reparto</div>
                            <h3 class="fw-bold text-dark my-1">{{ $pendientesReparto ?? 0 }}</h3>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                Listos en Almacén
                            </span>
                        </div>
                        <div class="bg-warning text-dark rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            📦
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flota de Camiones -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Flota Activa</div>
                            <h3 class="fw-bold text-dark my-1">{{ $camionesActivos ?? 0 }} / {{ $totalCamiones ?? 0 }}</h3>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill">
                                Camiones Operativos
                            </span>
                        </div>
                        <div class="bg-info text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            🚚
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de Stock Bajo -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm border-start border-4 border-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold small">Stock Crítico</div>
                            <h3 class="fw-bold text-danger my-1">{{ $productosStockBajo ?? 0 }}</h3>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                Refrescos bajo mínimos
                            </span>
                        </div>
                        <div class="bg-danger text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            ⚠️
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN INFERIOR: TABLA DE PREVENTAS Y MÓDULO DE STOCK/ACCESOS -->
    <div class="row g-3">
        <!-- Tabla de Últimas Preventas -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <h5 class="fw-bold text-dark mb-0">📋 ÚLTIMAS PREVENTAS</h5>
                    <a href="{{ route('preventas.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                        Ver Lista Completa &rarr;
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Preventista</th>
                                <th>Estado</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasPreventas ?? [] as $p)
                                <tr>
                                    <td class="fw-bold">
                                        <a href="{{ route('preventas.show', $p) }}" class="text-decoration-none">
                                            {{ $p->codigo }}
                                        </a>
                                    </td>
                                    <td>{{ $p->cliente->nombre ?? 'N/A' }}</td>
                                    <td>{{ $p->preventista->nombre ?? 'N/A' }}</td>
                                    <td>
                                        @if($p->estado === 'Pendiente')
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        @elseif($p->estado === 'En Reparto')
                                            <span class="badge bg-info text-white">En Reparto</span>
                                        @elseif($p->estado === 'Entregado')
                                            <span class="badge bg-success">Entregado</span>
                                        @else
                                            <span class="badge bg-secondary">Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Bs {{ number_format($p->total, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No hay preventas registradas en la jornada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panel Lateral: Alertas e Accesos Rápidos -->
        <div class="col-lg-4 d-flex flex-column gap-3">
            <!-- Alertas de Paquetes en Almacén -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-dark mb-0">🥤 Productos Poco Stock</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush border-top">
                        @forelse($productosCriticos ?? [] as $prod)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <div class="fw-bold text-dark">{{ $prod->nombre }}</div>
                                    <small class="text-muted">{{ $prod->unidadesPorPaquete }} un. por pack</small>
                                </div>
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    {{ $prod->paquetesDisponibles() }} packs
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">
                                Todos los refrescos tienen stock suficiente.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Accesos Rápidos de Logística -->
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-2">🚛 Control de Distribución</h5>
                    <p class="small opacity-75 mb-3">
                        Administra las unidades de transporte y monitorea los recorridos programados.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('camiones.index') }}" class="btn btn-light fw-bold text-primary btn-sm">
                            Gestionar Camiones
                        </a>
                        @if(in_array(auth()->user()->rol->nombre, ['Administrador', 'Repartidor']))

                            <a href="{{ route('rutas.index') }}"
                            class="btn btn-outline-light btn-sm">

                                Rutas de Cobertura

                            </a>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection