<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preventa extends Model
{
    protected $table = 'preventas'; // Aseguramos el nombre de la tabla
    protected $primaryKey = 'idPreventa';

    protected $fillable = [
        'codigo',
        'idCliente',
        'idPreventista',
        'fecha',
        'total',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'float',
    ];

    // Solo se puede modificar mientras no haya sido enviada a distribución (En Reparto/Entregado) ni Cancelada
    public function esModificable(): bool
    {
        return $this->estado === 'Pendiente';
    }

    // Solo se puede cancelar si aún no salió a reparto
    public function esCancelable(): bool
    {
        return $this->estado === 'Pendiente';
    }

    public function estaEnReparto(): bool
    {
        return $this->estado === 'En Reparto';
    }

    public function cliente()
    {
        return $this->belongsTo(
            Cliente::class, 
            'idCliente', 
            'idCliente');
    }

    public function preventista()
    {
        return $this->belongsTo(
            Usuario::class, 
            'idPreventista', 
            'idUsuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePreventa::class, 'idPreventa', 'idPreventa');
    }

    public function calcularTotal(): float
    {
        return (float) $this->detalles()->sum('subtotal');
    }
    public function rutas()
    {
        return $this->belongsToMany(
            Ruta::class,
            'ruta_preventa',
            'idPreventa',
            'idRuta',
            'idPreventa',
            'idRuta'
        )->withPivot('fechaAsignacion')
         ->withTimestamps();
    }
}