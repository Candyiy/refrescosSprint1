@extends('layouts.app')
@section('title', 'Detalle de Oferta')

@section('content')
    <h3>{{ $oferta->nombre }}</h3>
    <p><strong>Descuento:</strong> {{ $oferta->descuento }}%</p>
    <p><strong>Vigencia:</strong> {{ $oferta->fechaInicio->format('d/m/Y') }} - {{ $oferta->fechaFin->format('d/m/Y') }}</p>
    <p><strong>Estado:</strong>
        <span class="badge bg-{{ $oferta->estado ? 'success' : 'secondary' }}">
            {{ $oferta->estado ? 'Activa' : 'De baja' }}
        </span>
    </p>

    <h5>Productos incluidos</h5>
    <ul class="list-group mb-3">
        @foreach ($oferta->productos as $p)
            <li class="list-group-item d-flex justify-content-between">
                {{ $p->nombre }}
                <span>Bs {{ number_format($p->precio, 2) }} &rarr; Bs {{ number_format($p->precio * (1 - $oferta->descuento / 100), 2) }}</span>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('ofertas.index') }}" class="btn btn-secondary">Volver</a>
@endsection
