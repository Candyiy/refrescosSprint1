<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model {
    protected $primaryKey = 'idRuta';
    protected $fillable = ['nombre', 'zona', 'estado'];
    protected $table = 'rutas';
    public function preventas()
    {
        return $this->belongsToMany(
            Preventa::class,
            'ruta_preventa',
            'idRuta',
            'idPreventa',
            'idRuta',
            'idPreventa'
        )->withPivot('fechaAsignacion')
         ->withTimestamps();
    }
}
