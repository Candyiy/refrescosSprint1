<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'idUsuario';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'idRol',
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'correo',
        'usuario',
        'contrasena',
        'estado',
    ];

    protected $hidden = [
        'contrasena',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Campo utilizado por Laravel para verificar
     * la contraseña del usuario.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Relación con el rol.
     */
    public function rol()
    {
        return $this->belongsTo(
            Rol::class,
            'idRol',
            'idRol'
        );
    }
}
