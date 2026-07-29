<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('preventas', function (Blueprint $table) {
            $table->id('idPreventa');
            $table->string('codigo')->unique(); // ej: PV-000001
            $table->foreignId('idCliente')->constrained('clientes', 'idCliente');
            $table->foreignId('idPreventista')->constrained('usuarios', 'idUsuario');
            $table->date('fecha');
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['Pendiente', 'Entregado', 'Cancelado'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('preventas'); }
};
