@extends('layouts.app')
@section('title', 'Editar Devolución')

@section('content')
    <div class="container py-3">
        <h3 class="fw-bold mb-3">✏️ Editar Devolución #{{ $devolucion->idDevolucion }}</h3>

        <form method="POST" action="{{ route('devoluciones.update', $devolucion) }}">
            @csrf
            @method('PUT')

            @include('devoluciones._form', ['devolucion' => $devolucion])

            <div class="mt-3">
                <button type="submit" class="btn btn-primary fw-bold">Guardar Cambios</button>
                <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection