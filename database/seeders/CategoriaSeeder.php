<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            [
                'nombre' => 'Pasteles',
                'descripcion' => 'Deliciosos pasteles caseros'
            ],
            [
                'nombre' => 'Galletas',
                'descripcion' => 'Galletas artesanales'
            ],
            [
                'nombre' => 'Postres',
                'descripcion' => 'Postres especiales'
            ],
            [
                'nombre' => 'Tartas',
                'descripcion' => 'Tartas caseras'
            ],
            [
                'nombre' => 'Donuts',
                'descripcion' => 'Donuts frescos'
            ],
            [
                'nombre' => 'Roles',
                'descripcion' => 'Roles caseros'
            ],
            [
                'nombre' => 'Pan',
                'descripcion' => 'Pan artesanal'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
