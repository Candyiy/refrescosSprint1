@extends('layouts.app')
@section('title', 'Nueva Ruta')

@section('content')
    <h3>Registrar Ruta</h3>
    <form method="POST" action="{{ route('rutas.store') }}">
        @csrf
        @include('rutas._form', ['ruta' => null])
        <button class="btn btn-primary mt-3">Guardar</button>
        <a href="{{ route('rutas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
