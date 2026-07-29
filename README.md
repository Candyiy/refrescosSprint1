# CRUD de Preventa y Ofertas — Laravel

Módulos generados según el diagrama entidad-relación y el diagrama de clases del proyecto
"Sistema de Gestión de Preventas de Refrescos".

## Instalación en tu proyecto Laravel existente

1. Copia las carpetas a tu proyecto (respetando la estructura):
   - `database/migrations/*.php` → `database/migrations/`
   - `app/Models/*.php` → `app/Models/`
   - `app/Http/Controllers/*.php` → `app/Http/Controllers/`
   - `resources/views/preventas/`, `resources/views/ofertas/`, `resources/views/layouts/` → `resources/views/`
   - `routes/preventa_oferta.php` → `routes/`

2. En `routes/web.php` agrega al final:
   ```php
   require __DIR__.'/preventa_oferta.php';
   ```

3. Corre las migraciones:
   ```bash
   php artisan migrate
   ```

4. (Opcional) Crea un `PreventaSeeder` / `ProductoSeeder` para tener datos de prueba
   (Categoria, Producto, Cliente, Rol, Usuario) antes de probar el CRUD, ya que
   Preventa y DetallePreventa dependen de esas tablas.

## Reglas de negocio implementadas

**Preventa**
- Estados: `Pendiente` → `Entregado` | `Cancelado`.
- Solo se puede **editar** una preventa mientras esté en estado `Pendiente`
  (no enviada a distribución).
- Solo se puede **cancelar** una preventa `Pendiente`; al cancelar se libera el stock reservado.
- Una preventa `Entregado` **no puede eliminarse**.
- Validaciones: cliente debe existir, cantidad > 0, stock disponible suficiente,
  no se permiten productos duplicados en la misma preventa, el total se calcula
  automáticamente a partir del detalle.
- Búsqueda por código, cliente y fecha desde el listado (`GET /preventas`).

**Oferta**
- Según el diagrama de clases, usa **baja lógica** (`darBaja()`) en vez de eliminación
  física — cambia `estado` a `false`. Puede reactivarse si aún no venció (`fechaFin`).
- Relación N:N con Producto vía tabla pivote `oferta_producto`.
- Validación: `fechaFin >= fechaInicio`, descuento entre 0.01 y 100, al menos un producto.

## Rutas generadas

| Método | URI | Acción |
|---|---|---|
| GET | /preventas | index (con filtros ?codigo=&idCliente=&fecha=) |
| GET | /preventas/create | create |
| POST | /preventas | store |
| GET | /preventas/{id} | show |
| GET | /preventas/{id}/edit | edit |
| PUT | /preventas/{id} | update |
| PATCH | /preventas/{id}/cancelar | cancelar |
| PATCH | /preventas/{id}/entregar | marcarEntregado |
| DELETE | /preventas/{id} | destroy (solo si no está Entregado) |
| GET | /ofertas | index (con filtro ?nombre=) |
| GET | /ofertas/create | create |
| POST | /ofertas | store |
| GET | /ofertas/{id} | show |
| GET | /ofertas/{id}/edit | edit |
| PUT | /ofertas/{id} | update |
| PATCH | /ofertas/{id}/dar-baja | darBaja |
| PATCH | /ofertas/{id}/reactivar | reactivar |
