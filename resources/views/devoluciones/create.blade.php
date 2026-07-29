@extends('layouts.app')
@section('title', 'Nueva Devolución')

@section('content')
    <h3>Registrar Devolución</h3>
    <form method="POST" action="{{ route('devoluciones.store') }}">
        @csrf
        @include('devoluciones._form', ['devolucion' => null])
        <button class="btn btn-primary mt-3">Guardar</button>
        <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
