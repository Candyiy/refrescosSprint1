@extends('layouts.app')
@section('title', 'Detalle de Camión')

@section('content')
    <h3>Camión {{ $camion->placa ?? '' }}</h3>
    
    {{-- Muestra un mensaje amigable si el conductor viene vacío o NULL --}}
    <p><strong>Conductor:</strong> {{ !empty($camion->conductor) ? $camion->conductor : 'Sin conductor asignado' }}</p>
    
    <p><strong>Teléfono:</strong> {{ !empty($camion->telefono) ? $camion->telefono : '-' }}</p>
    
    {{-- Soporta tanto booleanos (1/0) como cadenas de texto ('Activo'/'Inactivo') --}}
    @php
        $esActivo = $camion->estado === 1 || $camion->estado === true || strtolower($camion->estado ?? '') === 'activo';
    @endphp

    <p><strong>Estado:</strong> 
        <span class="badge bg-{{ $esActivo ? 'success' : 'secondary' }}">
            {{ $esActivo ? 'Activo' : 'Inactivo' }}
        </span>
    </p>

    <a href="{{ route('camiones.index') }}" class="btn btn-secondary">Volver</a>
@endsection