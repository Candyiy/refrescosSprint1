@extends('layouts.app')
@section('title', 'Nuevo Camión')

@section('content')
    <h3>Registrar Camión</h3>
    <form method="POST" action="{{ route('camiones.store') }}">
        @csrf
        @include('camiones._form', ['camion' => null])
        <button class="btn btn-primary mt-3">Guardar</button>
        <a href="{{ route('camiones.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
