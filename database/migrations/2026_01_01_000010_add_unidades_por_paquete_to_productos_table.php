<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('productos', function (Blueprint $table) {
            // Cantidad de unidades individuales que trae cada paquete (ej. pack x6 = 6)
            // precio pasa a interpretarse como "precio por paquete"
            $table->integer('unidadesPorPaquete')->default(1)->after('presentacion');
        });
    }
    public function down(): void {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('unidadesPorPaquete');
        });
    }
};
