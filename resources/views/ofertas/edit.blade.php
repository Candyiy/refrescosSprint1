@extends('layouts.app')
@section('title', 'Editar Oferta')

@section('content')
    <h3>Editar Oferta</h3>
    <form method="POST" action="{{ route('ofertas.update', $oferta) }}">
        @csrf
        @method('PUT')
        @include('ofertas._form', ['oferta' => $oferta])
        <button class="btn btn-primary mt-3">Guardar Cambios</button>
        <a href="{{ route('ofertas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
