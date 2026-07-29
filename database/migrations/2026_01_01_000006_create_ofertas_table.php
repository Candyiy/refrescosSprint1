<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id('idOferta');
            $table->string('nombre');
            $table->decimal('descuento', 5, 2); // porcentaje
            $table->date('fechaInicio');
            $table->date('fechaFin');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ofertas'); }
};
