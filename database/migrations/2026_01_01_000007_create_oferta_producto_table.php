<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('oferta_producto', function (Blueprint $table) {
            $table->foreignId('idOferta')->constrained('ofertas', 'idOferta')->cascadeOnDelete();
            $table->foreignId('idProducto')->constrained('productos', 'idProducto')->cascadeOnDelete();
            $table->primary(['idOferta', 'idProducto']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('oferta_producto'); }
};
