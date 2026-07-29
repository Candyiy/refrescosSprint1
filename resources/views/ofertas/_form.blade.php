{{-- Partial usado por create.blade.php y edit.blade.php --}}
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $oferta->nombre ?? '') }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Descuento (%)</label>
        <input type="number" step="0.01" min="0.01" max="100" name="descuento" class="form-control"
               value="{{ old('descuento', $oferta->descuento ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Fecha Inicio</label>
        <input type="date" name="fechaInicio" class="form-control"
               value="{{ old('fechaInicio', isset($oferta->fechaInicio) ? $oferta->fechaInicio->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Fecha Fin</label>
        <input type="date" name="fechaFin" class="form-control"
               value="{{ old('fechaFin', isset($oferta->fechaFin) ? $oferta->fechaFin->format('Y-m-d') : '') }}" required>
    </div>
</div>

<hr class="my-4">
<h5>Productos incluidos en la oferta</h5>
<div class="row row-cols-2 row-cols-md-3 g-2">
    @foreach ($productos as $p)
        <div class="col">
            <div class="form-check border rounded p-2">
                <input class="form-check-input" type="checkbox" name="productos[]" value="{{ $p->idProducto }}"
                       id="prod-{{ $p->idProducto }}"
                       @checked(in_array($p->idProducto, old('productos', $productosSeleccionados ?? [])))>
                <label class="form-check-label" for="prod-{{ $p->idProducto }}">
                    {{ $p->nombre }} <small class="text-muted">(Bs {{ number_format($p->precio, 2) }})</small>
                </label>
            </div>
        </div>
    @endforeach
</div>
