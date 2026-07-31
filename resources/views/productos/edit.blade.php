@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Editar Producto</h3>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            ← Volver a Productos
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('productos.update', $producto) }}" method="POST">
                @csrf
                @method('PUT')
                @include('productos.form')
        </div>
    </div>
</div>

@endsection
