<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ruta_preventa', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('idRuta')
                ->constrained('rutas', 'idRuta')
                ->cascadeOnDelete();

            $table->foreignId('idPreventa')
                ->constrained('preventas', 'idPreventa')
                ->cascadeOnDelete();

            $table->date('fechaAsignacion');

            $table->timestamps();

            $table->unique(['idRuta', 'idPreventa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_preventa');
    }
};
