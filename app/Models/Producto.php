<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idProducto';

    protected $fillable = [
        'idCategoria',
        'codigo',
        'nombre',
        'presentacion',
        'precio',
        'stock',
        'unidadesPorPaquete', // Se añade para habilitar la venta por paquetes
        'estado'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    }

    public function ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'oferta_producto', 'idProducto', 'idOferta');
    }

    // Oferta activa vigente hoy (si tiene varias, toma el mayor descuento)
    public function ofertaVigente()
    {
        return $this->ofertas()
            ->where('estado', true)
            ->whereDate('fechaInicio', '<=', now())
            ->whereDate('fechaFin', '>=', now())
            ->orderByDesc('descuento')
            ->first();
    }

    // Precio final aplicando la oferta vigente (si existe)
    public function precioConOferta(): float
    {
        $oferta = $this->ofertaVigente();
        if ($oferta) {
            return round($this->precio * (1 - $oferta->descuento / 100), 2);
        }
        return (float) $this->precio;
    }

    // Retorna la cantidad de paquetes completos disponibles según el stock actual
    public function paquetesDisponibles(): int
    {
        $unidades = max(1, $this->unidadesPorPaquete ?? 1);
        return (int) floor($this->stock / $unidades);
    }
}