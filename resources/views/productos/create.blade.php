@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Nuevo Producto</h3>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            ← Volver a Productos
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-muted">Completa los campos para registrar el producto</p>

            <form action="{{ route('productos.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Código - BLOQUEADO -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                                   value="{{ old('codigo', $codigoSugerido ?? '') }}"
                                   readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <small class="text-muted">El código se genera automáticamente y no se puede modificar</small>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $producto->nombre ?? '') }}"
                                   placeholder="Ej. Coca-Cola 600ml" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select name="idCategoria" class="form-control @error('idCategoria') is-invalid @enderror" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}"
                                        {{ old('idCategoria', $producto->idCategoria ?? '') == $categoria->idCategoria ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idCategoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Presentación - Ahora como SELECT -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Presentación <span class="text-danger">*</span></label>
                            <select name="presentacion" class="form-control @error('presentacion') is-invalid @enderror" required>
                                <option value="">Seleccionar presentación</option>
                                <option value="Botella PET" {{ old('presentacion', $producto->presentacion ?? '') == 'Botella PET' ? 'selected' : '' }}>Botella PET</option>
                                <option value="Lata" {{ old('presentacion', $producto->presentacion ?? '') == 'Lata' ? 'selected' : '' }}>Lata</option>
                                <option value="Botella Vidrio" {{ old('presentacion', $producto->presentacion ?? '') == 'Botella Vidrio' ? 'selected' : '' }}>Botella Vidrio</option>
                                <option value="Tetra Pak" {{ old('presentacion', $producto->presentacion ?? '') == 'Tetra Pak' ? 'selected' : '' }}>Tetra Pak</option>
                                <option value="Bolsa" {{ old('presentacion', $producto->presentacion ?? '') == 'Bolsa' ? 'selected' : '' }}>Bolsa</option>
                                <option value="Caja" {{ old('presentacion', $producto->presentacion ?? '') == 'Caja' ? 'selected' : '' }}>Caja</option>
                                <option value="Frasco" {{ old('presentacion', $producto->presentacion ?? '') == 'Frasco' ? 'selected' : '' }}>Frasco</option>
                                <option value="Sachet" {{ old('presentacion', $producto->presentacion ?? '') == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                <option value="Unidad" {{ old('presentacion', $producto->presentacion ?? '') == 'Unidad' ? 'selected' : '' }}>Unidad</option>
                            </select>
                            @error('presentacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Precio Unitario -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Precio Unitario (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="precio"
                                   class="form-control @error('precio') is-invalid @enderror"
                                   value="{{ old('precio', $producto->precio ?? '') }}"
                                   placeholder="0.00" required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock Inicial -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stock Inicial <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $producto->stock ?? '') }}"
                                   placeholder="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="1" {{ old('estado', $producto->estado ?? 1) == 1 ? 'selected' : '' }}>✅ Activo</option>
                                <option value="0" {{ old('estado', $producto->estado ?? 1) == 0 ? 'selected' : '' }}>❌ Inactivo</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="reset" class="btn btn-secondary">🔄 Limpiar</button>
                    <button type="submit" class="btn btn-primary">💾 Guardar Producto</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
