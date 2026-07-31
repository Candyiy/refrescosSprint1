<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'idCategoria' => 1,
                'nombre' => 'Gaseosas',
                'descripcion' => 'Refrescos gaseosos de diferentes sabores y presentaciones.'
            ],
            [
                'idCategoria' => 2,
                'nombre' => 'Agua',
                'descripcion' => 'Agua embotellada natural y mineral.'
            ],
            [
                'idCategoria' => 3,
                'nombre' => 'Jugos',
                'descripcion' => 'Jugos y bebidas frutales embotelladas.'
            ],
            [
                'idCategoria' => 4,
                'nombre' => 'Energizantes',
                'descripcion' => 'Bebidas energéticas y estimulantes.'
            ],
            [
                'idCategoria' => 5,
                'nombre' => 'Bebidas Deportivas',
                'descripcion' => 'Bebidas hidratantes para actividades deportivas.'
            ],
            [
                'idCategoria' => 6,
                'nombre' => 'Tés',
                'descripcion' => 'Bebidas de té listas para consumir.'
            ],
            [
                'idCategoria' => 7,
                'nombre' => 'Bebidas Saborizadas',
                'descripcion' => 'Bebidas con diferentes sabores frutales.'
            ],
            [
                'idCategoria' => 8,
                'nombre' => 'Gaseosas Sin Azúcar',
                'descripcion' => 'Gaseosas con cero azúcar.'
            ],
            [
                'idCategoria' => 9,
                'nombre' => 'Bebidas Familiares',
                'descripcion' => 'Presentaciones grandes para consumo familiar.'
            ],
            [
                'idCategoria' => 10,
                'nombre' => 'Bebidas Personales',
                'descripcion' => 'Presentaciones individuales para consumo personal.'
            ],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert([
                'idCategoria' => $categoria['idCategoria'],
                'nombre' => $categoria['nombre'],
                'descripcion' => $categoria['descripcion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
