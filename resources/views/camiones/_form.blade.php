<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Placa</label>
        <input type="text" name="placa" class="form-control" value="{{ old('placa', $camion->placa ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Conductor</label>
        <input type="text" name="conductor" class="form-control" value="{{ old('conductor', $camion->conductor ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $camion->telefono ?? '') }}">
    </div>
</div>
