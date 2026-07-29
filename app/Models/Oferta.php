<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model {
    protected $primaryKey = 'idOferta';
    protected $fillable = ['nombre', 'descuento', 'fechaInicio', 'fechaFin', 'estado'];
    protected $casts = [
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
        'estado' => 'boolean',
    ];

    public function productos() {
        return $this->belongsToMany(Producto::class, 'oferta_producto', 'idOferta', 'idProducto');
    }
}
