<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['idRol' => 1, 'nombre' => 'Administrador'],
            ['idRol' => 2, 'nombre' => 'Preventista'],
            ['idRol' => 3, 'nombre' => 'Encargado de Almacén'],
            ['idRol' => 4, 'nombre' => 'Supervisor'],
            ['idRol' => 5, 'nombre' => 'Vendedor'],
            ['idRol' => 6, 'nombre' => 'Repartidor'],
            ['idRol' => 7, 'nombre' => 'Cliente'],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->insert([
                'idRol' => $rol['idRol'],
                'nombre' => $rol['nombre'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
