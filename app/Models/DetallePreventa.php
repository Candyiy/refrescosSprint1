<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetallePreventa extends Model {
    protected $primaryKey = 'idDetalle';
    protected $fillable = ['idPreventa', 'idProducto', 'cantidad', 'precio', 'subtotal'];

    public function preventa() {
        return $this->belongsTo(Preventa::class, 'idPreventa', 'idPreventa');
    }

    public function producto() {
        return $this->belongsTo(Producto::class, 'idProducto', 'idProducto');
    }
}
