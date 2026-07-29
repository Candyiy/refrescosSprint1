<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    protected $primaryKey = 'idCliente';
    protected $fillable = ['nitCi', 'nombre', 'direccion', 'telefono', 'estado'];

    public function preventas() {
        return $this->hasMany(Preventa::class, 'idCliente', 'idCliente');
    }
}
