<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    protected $table = 'camiones';
    protected $primaryKey = 'idCamion';

    protected $fillable = [
        'placa',
        'conductor',
        'telefono',
        'estado'
    ];

    /**
     * Indica a Laravel que la columna para el binding de rutas es idCamion
     */
    public function getRouteKeyName()
    {
        return 'idCamion';
    }
}