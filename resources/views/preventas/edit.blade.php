@extends('layouts.app')
@section('title', 'Editar Preventa')

@section('content')
    <h3>Editar Preventa {{ $preventa->codigo }}</h3>

    <form method="POST" action="{{ route('preventas.update', $preventa) }}" id="form-preventa">
        @csrf
        @method('PUT')
        @include('preventas._form', ['preventa' => $preventa])

        <button class="btn btn-primary mt-3">Guardar Cambios</button>
        <a href="{{ route('preventas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
