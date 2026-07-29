<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->foreignId('idRol')->constrained('roles', 'idRol');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('ci')->unique();
            $table->string('telefono')->nullable();
            $table->string('correo')->unique();
            $table->string('usuario')->unique();
            $table->string('contrasena');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('usuarios'); }
};
