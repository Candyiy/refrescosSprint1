<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('camiones', function (Blueprint $table) {
            $table->id('idCamion');
            $table->string('placa')->unique();
            $table->string('conductor');
            $table->string('telefono')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('camiones'); }
};
