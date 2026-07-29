@extends('layouts.app')
@section('title', 'Nueva Preventa')

@section('content')
    <h3>Registrar Preventa</h3>

    <form method="POST" action="{{ route('preventas.store') }}" id="form-preventa">
        @csrf
        @include('preventas._form', ['preventa' => null, 'codigo' => $codigoSugerido])

        <button class="btn btn-primary mt-3">Guardar Preventa</button>
        <a href="{{ route('preventas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
