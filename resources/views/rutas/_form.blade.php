<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre de la ruta</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $ruta->nombre ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Zona</label>
        <input type="text" name="zona" class="form-control" value="{{ old('zona', $ruta->zona ?? '') }}" required>
    </div>
</div>
