@extends('layouts.app')

@section('title', 'Asignar pedidos a ruta')

@section('content')

<div class="container">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3>Asignar pedidos a ruta</h3>

            <p class="text-muted mb-0">
                Ruta:
                <strong>{{ $ruta->nombre }}</strong>
                |
                Zona:
                <strong>{{ $ruta->zona }}</strong>
            </p>
        </div>

        <a href="{{ route('rutas.index') }}"
           class="btn btn-secondary">
            ← Volver
        </a>

    </div>


    {{-- MENSAJES --}}

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- ERRORES DE VALIDACIÓN --}}

    @if($errors->any())
        <div class="alert alert-danger">

            <strong>Se encontraron errores:</strong>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- PEDIDOS DISPONIBLES --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Pedidos disponibles para asignar
                </strong>

                <span class="badge bg-light text-dark">
                    {{ $preventas->count() }} pedidos
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($preventas->count() > 0)

                <form
                    action="{{ route('rutas.asignarPedido', $ruta) }}"
                    method="POST">

                    @csrf


                    {{-- SELECCIONAR TODOS --}}

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                id="seleccionarTodos">

                                ☑ Seleccionar todos

                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary"
                                id="deseleccionarTodos">

                                ☐ Deseleccionar todos

                            </button>

                        </div>

                        <span class="text-muted">

                            Seleccionados:
                            <strong id="contador">0</strong>

                        </span>

                    </div>


                    {{-- TABLA DE PEDIDOS --}}

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">

                                <tr>

                                    <th style="width: 50px;">
                                        #
                                    </th>

                                    <th>
                                        Código
                                    </th>

                                    <th>
                                        Cliente
                                    </th>

                                    <th>
                                        Fecha
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($preventas as $preventa)

                                    <tr>

                                        <td class="text-center">

                                            <input
                                                type="checkbox"
                                                name="idPreventas[]"
                                                value="{{ $preventa->idPreventa }}"
                                                class="form-check-input pedido-checkbox">

                                        </td>

                                        <td>

                                            <strong>
                                                {{ $preventa->codigo }}
                                            </strong>

                                        </td>

                                        <td>

                                            {{ $preventa->cliente->nombre }}

                                            @if($preventa->cliente->telefono)
                                                <br>
                                                <small class="text-muted">
                                                    {{ $preventa->cliente->telefono }}
                                                </small>
                                            @endif

                                        </td>

                                        <td>
                                            {{ $preventa->fecha }}
                                        </td>

                                        <td>

                                            <strong>
                                                Bs.
                                                {{ number_format($preventa->total, 2) }}
                                            </strong>

                                        </td>

                                        <td>

                                            @if($preventa->estado == 'Pendiente')

                                                <span class="badge bg-warning text-dark">
                                                    Pendiente
                                                </span>

                                            @elseif($preventa->estado == 'En Reparto')

                                                <span class="badge bg-primary">
                                                    En Reparto
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- BOTON ASIGNAR --}}

                    <div class="d-flex justify-content-end mt-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="btnAsignar"
                            disabled>

                            🚚 Asignar pedidos seleccionados

                        </button>

                    </div>

                </form>

            @else

                <div class="alert alert-warning mb-0">

                    No existen preventas disponibles para asignar.

                </div>

            @endif

        </div>

    </div>


    {{-- PEDIDOS ASIGNADOS --}}

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Pedidos asignados a esta ruta
                </strong>

                <span class="badge bg-light text-dark">

                    {{ $pedidosAsignados->count() }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-striped mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Código</th>

                            <th>Cliente</th>

                            <th>Fecha</th>

                            <th>Total</th>

                            <th>Estado</th>

                            <th>Asignación</th>

                            <th>Acciones</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($pedidosAsignados as $preventa)

                            <tr>

                                <td>
                                    {{ $preventa->idPreventa }}
                                </td>

                                <td>

                                    <strong>
                                        {{ $preventa->codigo }}
                                    </strong>

                                </td>

                                <td>
                                    {{ $preventa->cliente->nombre }}
                                </td>

                                <td>
                                    {{ $preventa->fecha }}
                                </td>

                                <td>

                                    Bs.
                                    {{ number_format($preventa->total, 2) }}

                                </td>

                                <td>

                                    @if($preventa->estado == 'Pendiente')

                                        <span class="badge bg-warning text-dark">
                                            Pendiente
                                        </span>

                                    @elseif($preventa->estado == 'En Reparto')

                                        <span class="badge bg-primary">
                                            En Reparto
                                        </span>

                                    @elseif($preventa->estado == 'Entregado')

                                        <span class="badge bg-success">
                                            Entregado
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Cancelado
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $preventa->pivot->fechaAsignacion }}

                                </td>

                                <td>

                                    <form
                                        action="{{ route(
                                            'rutas.quitarPedido',
                                            [
                                                'ruta' => $ruta,
                                                'idPreventa' => $preventa->idPreventa
                                            ]
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            '¿Quitar este pedido de la ruta?'
                                        )">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger">

                                            Quitar

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-4">

                                    No hay pedidos asignados
                                    a esta ruta.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- JAVASCRIPT --}}

<script>

    const checkboxes = document.querySelectorAll('.pedido-checkbox');

    const contador = document.getElementById('contador');

    const btnAsignar = document.getElementById('btnAsignar');

    const seleccionarTodos =
        document.getElementById('seleccionarTodos');

    const deseleccionarTodos =
        document.getElementById('deseleccionarTodos');


    function actualizarContador() {

        const seleccionados =
            document.querySelectorAll(
                '.pedido-checkbox:checked'
            ).length;

        contador.textContent = seleccionados;

        btnAsignar.disabled = seleccionados === 0;

    }


    checkboxes.forEach(function(checkbox) {

        checkbox.addEventListener(
            'change',
            actualizarContador
        );

    });


    seleccionarTodos.addEventListener('click', function() {

        checkboxes.forEach(function(checkbox) {

            checkbox.checked = true;

        });

        actualizarContador();

    });


    deseleccionarTodos.addEventListener('click', function() {

        checkboxes.forEach(function(checkbox) {

            checkbox.checked = false;

        });

        actualizarContador();

    });

</script>

@endsection