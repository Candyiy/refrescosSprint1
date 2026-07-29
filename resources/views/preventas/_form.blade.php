{{-- Partial usado por create.blade.php y edit.blade.php --}}
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control"
               value="{{ old('codigo', $preventa->codigo ?? $codigo ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Cliente</label>
        <select name="idCliente" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach ($clientes as $c)
                <option value="{{ $c->idCliente }}" @selected(old('idCliente', $preventa->idCliente ?? null) == $c->idCliente)>
                    {{ $c->nombre }} ({{ $c->nitCi }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Preventista</label>
        <select name="idPreventista" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach ($preventistas as $u)
                <option value="{{ $u->idUsuario }}" @selected(old('idPreventista', $preventa->idPreventista ?? null) == $u->idUsuario)>
                    {{ $u->nombre }} {{ $u->apellido }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Fecha</label>
        {{-- 🔒 APLICADA RESTRICCIÓN LOGÍSTICA: min="{{ date('Y-m-d') }}" --}}
        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
               value="{{ old('fecha', isset($preventa->fecha) ? \Carbon\Carbon::parse($preventa->fecha)->format('Y-m-d') : now()->format('Y-m-d')) }}" 
               min="{{ date('Y-m-d') }}" 
               required>
        @error('fecha')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $preventa->observaciones ?? '') }}</textarea>
    </div>
</div>

<hr class="my-4">
<h5>Productos</h5>
<table class="table table-bordered bg-white" id="tabla-lineas">
    <thead class="table-light">
        <tr>
            <th style="width:35%">Producto</th>
            <th style="width:15%">Paquetes disp.</th>
            <th style="width:15%">Precio x paquete</th>
            <th style="width:15%">Cant. (paquetes)</th>
            <th style="width:10%">Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="lineas-body"></tbody>
</table>
<small class="text-muted d-block mb-2">La cantidad se ingresa en <strong>paquetes</strong>, no en unidades sueltas. El stock se descuenta automáticamente en unidades según lo que trae cada paquete.</small>
<button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-linea">+ Agregar producto</button>

<div class="text-end mt-3">
    <h5>Total: Bs <span id="total-preventa">0.00</span></h5>
</div>

@php
    $productosJs = $productos->map(function ($p) {
        $oferta = $p->ofertaVigente();
        return [
            'id'                  => $p->idProducto,
            'nombre'              => $p->nombre,
            'precio'              => (float) $p->precioConOferta(),
            'precioOriginal'      => (float) $p->precio,
            'tieneOferta'         => (bool) $oferta,
            'descuento'           => $oferta ? (float) $oferta->descuento : 0,
            'paquetesDisponibles' => (int) $p->paquetesDisponibles(),
            'unidadesPorPaquete'  => (int) $p->unidadesPorPaquete,
        ];
    });

    $lineasExistentesJs = collect();
    if (old('productos')) {
        $lineasExistentesJs = collect(old('productos'))->map(function ($l) {
            return ['idProducto' => $l['idProducto'] ?? '', 'cantidad' => $l['cantidad'] ?? 1];
        });
    } elseif (isset($preventa) && $preventa->detalles) {
        $lineasExistentesJs = $preventa->detalles->map(function ($d) {
            return ['idProducto' => $d->idProducto, 'cantidad' => $d->cantidad];
        });
    }
@endphp

<script>
const productos = @json($productosJs);
const lineasExistentes = @json($lineasExistentesJs);

let contador = 0;

function crearFila(idProductoSeleccionado = '', cantidad = 1) {
    const tbody = document.getElementById('lineas-body');
    const idx = contador++;
    const tr = document.createElement('tr');
    tr.dataset.idx = idx;

    let options = '<option value="">-- Producto --</option>';
    productos.forEach(p => {
        const etiquetaOferta = p.tieneOferta ? ` 🔻${p.descuento}% OFERTA` : '';
        const etiquetaPack = p.unidadesPorPaquete > 1 ? ` (x${p.unidadesPorPaquete})` : '';
        options += `<option value="${p.id}" data-precio="${p.precio}" data-precio-original="${p.precioOriginal}" data-paquetes="${p.paquetesDisponibles}" ${p.id == idProductoSeleccionado ? 'selected' : ''}>${p.nombre}${etiquetaPack}${etiquetaOferta}</option>`;
    });

    tr.innerHTML = `
        <td>
            <select name="productos[${idx}][idProducto]" class="form-select select-producto" required>${options}</select>
        </td>
        <td class="celda-stock text-center">-</td>
        <td class="celda-precio text-center">-</td>
        <td><input type="number" min="1" name="productos[${idx}][cantidad]" class="form-control input-cantidad" value="${cantidad}" required></td>
        <td class="celda-subtotal text-center">0.00</td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-quitar">&times;</button></td>
    `;
    tbody.appendChild(tr);

    tr.querySelector('.select-producto').addEventListener('change', () => actualizarFila(tr));
    tr.querySelector('.input-cantidad').addEventListener('input', () => actualizarFila(tr));
    tr.querySelector('.btn-quitar').addEventListener('click', () => { tr.remove(); recalcularTotal(); });

    actualizarFila(tr);
}

function actualizarFila(tr) {
    const select = tr.querySelector('.select-producto');
    const opt = select.selectedOptions[0];
    const precio = opt && opt.dataset.precio ? parseFloat(opt.dataset.precio) : 0;
    const precioOriginal = opt && opt.dataset.precioOriginal ? parseFloat(opt.dataset.precioOriginal) : 0;
    const paquetes = opt && opt.dataset.paquetes ? parseInt(opt.dataset.paquetes) : 0;
    const cantidad = parseInt(tr.querySelector('.input-cantidad').value || 0);

    if (precio < precioOriginal && precio > 0) {
        tr.querySelector('.celda-precio').innerHTML = `<small class="text-muted text-decoration-line-through">${precioOriginal.toFixed(2)}</small><br><span class="text-success fw-bold">${precio.toFixed(2)}</span>`;
    } else {
        tr.querySelector('.celda-precio').textContent = precio.toFixed(2);
    }
    tr.querySelector('.celda-stock').textContent = paquetes;
    tr.querySelector('.celda-subtotal').textContent = (precio * cantidad).toFixed(2);

    recalcularTotal();
}

function recalcularTotal() {
    let total = 0;
    document.querySelectorAll('#lineas-body tr').forEach(tr => {
        total += parseFloat(tr.querySelector('.celda-subtotal').textContent || 0);
    });
    document.getElementById('total-preventa').textContent = total.toFixed(2);
}

document.getElementById('btn-add-linea').addEventListener('click', () => crearFila());

// Validación básica en cliente: no productos duplicados
const formPreventa = document.getElementById('form-preventa');
if (formPreventa) {
    formPreventa.addEventListener('submit', function (e) {
        const seleccionados = Array.from(document.querySelectorAll('.select-producto'))
            .map(s => s.value)
            .filter(val => val !== "");
            
        const unicos = new Set(seleccionados);
        if (unicos.size !== seleccionados.length) {
            e.preventDefault();
            alert('No se pueden registrar productos duplicados en la misma preventa.');
        }
    });
}

if (lineasExistentes.length > 0) {
    lineasExistentes.forEach(l => crearFila(l.idProducto, l.cantidad));
} else {
    crearFila();
}
</script>