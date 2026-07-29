<div class="row g-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Código <span class="text-danger">*</span></label>
            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                   value="{{ old('codigo', $producto->codigo ?? $codigoSugerido ?? '') }}"
                   readonly style="background-color: #e9ecef; cursor: not-allowed;">
            <small class="text-muted">El código se genera automáticamente</small>
            @error('codigo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

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
            </select>
            @error('presentacion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Precio por Paquete (Bs.) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0.01" name="precio"
                   class="form-control @error('precio') is-invalid @enderror"
                   value="{{ old('precio', $producto->precio ?? '') }}"
                   placeholder="0.00" required>
            @error('precio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Stock (Paquetes) <span class="text-danger">*</span></label>
            <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror"
                   value="{{ old('stock', $producto->stock ?? '') }}"
                   placeholder="0" required>
            <small class="text-muted">Cantidad de paquetes en inventario</small>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Unidades por Paquete <span class="text-danger">*</span></label>
            <input type="number" min="1" name="unidades_por_paquete"
                   class="form-control @error('unidades_por_paquete') is-invalid @enderror"
                   value="{{ old('unidades_por_paquete', $producto->unidades_por_paquete ?? 6) }}"
                   placeholder="6" required>
            <small class="text-muted">Ej: 6 unidades por paquete</small>
            @error('unidades_por_paquete')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Tipo de Paquete</label>
            <select name="tipo_paquete" class="form-control @error('tipo_paquete') is-invalid @enderror">
                <option value="">Seleccionar tipo</option>
                <option value="Caja" {{ old('tipo_paquete', $producto->tipo_paquete ?? '') == 'Caja' ? 'selected' : '' }}>📦 Caja</option>
                <option value="Display" {{ old('tipo_paquete', $producto->tipo_paquete ?? '') == 'Display' ? 'selected' : '' }}>📊 Display</option>
                <option value="Bolsa" {{ old('tipo_paquete', $producto->tipo_paquete ?? '') == 'Bolsa' ? 'selected' : '' }}>🛍️ Bolsa</option>
                <option value="Pallet" {{ old('tipo_paquete', $producto->tipo_paquete ?? '') == 'Pallet' ? 'selected' : '' }}>📦 Pallet</option>
                <option value="Pack" {{ old('tipo_paquete', $producto->tipo_paquete ?? '') == 'Pack' ? 'selected' : '' }}>📦 Pack</option>
            </select>
            @error('tipo_paquete')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
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

    <div class="col-md-12">
        <div class="alert alert-info">
            <strong>📦 Información de Stock:</strong><br>
            <span id="stock-info">
                @if(isset($producto))
                    Stock: <strong>{{ $producto->stock }}</strong> paquetes =
                    <strong>{{ $producto->stock * $producto->unidades_por_paquete }}</strong> unidades
                    <br>Tipo: {{ $producto->tipo_paquete ?? 'No especificado' }}
                @else
                    Complete los campos para ver el cálculo
                @endif
            </span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stockInput = document.querySelector('input[name="stock"]');
    const unidadesInput = document.querySelector('input[name="unidades_por_paquete"]');
    const stockInfo = document.getElementById('stock-info');

    function actualizarStockInfo() {
        const stock = parseInt(stockInput?.value) || 0;
        const unidades = parseInt(unidadesInput?.value) || 1;
        const totalUnidades = stock * unidades;
        stockInfo.innerHTML = `
            Stock: <strong>${stock}</strong> paquetes =
            <strong>${totalUnidades}</strong> unidades
            <br>${stock} paquete(s) × ${unidades} unidades = ${totalUnidades} unidades totales
        `;
    }

    if (stockInput && unidadesInput) {
        stockInput.addEventListener('input', actualizarStockInfo);
        unidadesInput.addEventListener('input', actualizarStockInfo);
    }
});
</script>
