<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // MySQL/MariaDB no permite modificar un ENUM directamente con Schema::table, se usa SQL crudo.
        DB::statement("ALTER TABLE preventas MODIFY estado ENUM('Pendiente', 'En Reparto', 'Entregado', 'Cancelado') DEFAULT 'Pendiente'");
    }
    public function down(): void {
        DB::statement("ALTER TABLE preventas MODIFY estado ENUM('Pendiente', 'Entregado', 'Cancelado') DEFAULT 'Pendiente'");
    }
};
