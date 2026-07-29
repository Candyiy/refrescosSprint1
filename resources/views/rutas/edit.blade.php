@extends('layouts.app')
@section('title', 'Editar Ruta')

@section('content')
    <h3>Editar Ruta</h3>
    <form method="POST" action="{{ route('rutas.update', $ruta) }}">
        @csrf
        @method('PUT')
        @include('rutas._form', ['ruta' => $ruta])
        <button class="btn btn-primary mt-3">Guardar Cambios</button>
        <a href="{{ route('rutas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
