<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';
    protected $primaryKey = 'idDevolucion';
    protected $fillable = ['idPreventa', 'idEncargado', 'fecha', 'motivo', 'observacion'];
    protected $casts = ['fecha' => 'date'];

    // 🔑 Le dice a Laravel que use 'idDevolucion' para generar las URLs automáticamente
    public function getRouteKeyName()
    {
        return 'idDevolucion';
    }

    public function preventa()
    {
        return $this->belongsTo(Preventa::class, 'idPreventa', 'idPreventa');
    }

    public function encargado()
    {
        return $this->belongsTo(Usuario::class, 'idEncargado', 'idUsuario');
    }
}