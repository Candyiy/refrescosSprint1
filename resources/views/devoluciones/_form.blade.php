<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Preventa (solo Entregadas)</label>
        <select name="idPreventa" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach ($preventas as $p)
                <option value="{{ $p->idPreventa }}" @selected(old('idPreventa', $devolucion->idPreventa ?? null) == $p->idPreventa)>
                    {{ $p->codigo }} — {{ $p->cliente->nombre }}
                </option>
            @endforeach
        </select>
        @if ($preventas->isEmpty())
            <small class="text-danger">No hay preventas en estado "Entregado" disponibles para devolución.</small>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Encargado de almacén</label>
        <select name="idEncargado" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach ($encargados as $u)
                <option value="{{ $u->idUsuario }}" @selected(old('idEncargado', $devolucion->idEncargado ?? null) == $u->idUsuario)>
                    {{ $u->nombre }} {{ $u->apellido }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control"
               value="{{ old('fecha', isset($devolucion->fecha) ? $devolucion->fecha->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Motivo</label>
        <textarea name="motivo" class="form-control" rows="2" required>{{ old('motivo', $devolucion->motivo ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Observación</label>
        <textarea name="observacion" class="form-control" rows="2">{{ old('observacion', $devolucion->observacion ?? '') }}</textarea>
    </div>
</div>
