@extends('layouts.app')
@section('title', 'Editar Camión')

@section('content')
    <h3>Editar Camión</h3>
    <form method="POST" action="{{ route('camiones.update', $camion) }}">
        @csrf
        @method('PUT')
        @include('camiones._form', ['camion' => $camion])
        <button class="btn btn-primary mt-3">Guardar Cambios</button>
        <a href="{{ route('camiones.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection