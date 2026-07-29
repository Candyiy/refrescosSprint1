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

                <div class="row g-3">
                    <!-- Código - BLOQUEADO -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                                   value="{{ old('codigo', $producto->codigo) }}"
                                   readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <small class="text-muted">El código no se puede modificar</small>
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
                                   value="{{ old('nombre', $producto->nombre) }}"
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
                                        {{ old('idCategoria', $producto->idCategoria) == $categoria->idCategoria ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idCategoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Presentación - SELECT -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Presentación <span class="text-danger">*</span></label>
                            <select name="presentacion" class="form-control @error('presentacion') is-invalid @enderror" required>
                                <option value="">Seleccionar presentación</option>
                                <option value="Botella PET" {{ old('presentacion', $producto->presentacion) == 'Botella PET' ? 'selected' : '' }}>Botella PET</option>
                                <option value="Lata" {{ old('presentacion', $producto->presentacion) == 'Lata' ? 'selected' : '' }}>Lata</option>
                                <option value="Botella Vidrio" {{ old('presentacion', $producto->presentacion) == 'Botella Vidrio' ? 'selected' : '' }}>Botella Vidrio</option>
                                <option value="Tetra Pak" {{ old('presentacion', $producto->presentacion) == 'Tetra Pak' ? 'selected' : '' }}>Tetra Pak</option>
                                <option value="Bolsa" {{ old('presentacion', $producto->presentacion) == 'Bolsa' ? 'selected' : '' }}>Bolsa</option>
                                <option value="Caja" {{ old('presentacion', $producto->presentacion) == 'Caja' ? 'selected' : '' }}>Caja</option>
                                <option value="Frasco" {{ old('presentacion', $producto->presentacion) == 'Frasco' ? 'selected' : '' }}>Frasco</option>
                                <option value="Sachet" {{ old('presentacion', $producto->presentacion) == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                <option value="Unidad" {{ old('presentacion', $producto->presentacion) == 'Unidad' ? 'selected' : '' }}>Unidad</option>
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
                                   value="{{ old('precio', $producto->precio) }}"
                                   placeholder="0.00" required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $producto->stock) }}"
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
                                <option value="1" {{ old('estado', $producto->estado) == 1 ? 'selected' : '' }}>✅ Activo</option>
                                <option value="0" {{ old('estado', $producto->estado) == 0 ? 'selected' : '' }}>❌ Inactivo</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">← Cancelar</a>
                    <button type="submit" class="btn btn-warning">Actualizar Producto</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
