<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {
    protected $primaryKey = 'idUsuario';
    protected $fillable = ['idRol', 'nombre', 'apellido', 'ci', 'telefono', 'correo', 'usuario', 'contrasena', 'estado'];
    protected $hidden = ['contrasena'];

    public function rol() {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }

    public function preventas() {
        return $this->hasMany(Preventa::class, 'idPreventista', 'idUsuario');
    }
}
