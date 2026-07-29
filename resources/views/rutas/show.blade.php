@extends('layouts.app')
@section('title', 'Detalle de Ruta')

@section('content')
    <h3>{{ $ruta->nombre }}</h3>
    <p><strong>Zona:</strong> {{ $ruta->zona }}</p>
    <p><strong>Estado:</strong> <span class="badge bg-{{ $ruta->estado ? 'success' : 'secondary' }}">{{ $ruta->estado ? 'Activa' : 'Inactiva' }}</span></p>
    <a href="{{ route('rutas.index') }}" class="btn btn-secondary">Volver</a>
@endsection
