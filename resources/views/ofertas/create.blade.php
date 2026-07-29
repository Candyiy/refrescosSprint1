@extends('layouts.app')
@section('title', 'Nueva Oferta')

@section('content')
    <h3>Registrar Oferta</h3>
    <form method="POST" action="{{ route('ofertas.store') }}">
        @csrf
        @include('ofertas._form', ['oferta' => null])
        <button class="btn btn-primary mt-3">Guardar Oferta</button>
        <a href="{{ route('ofertas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@endsection
