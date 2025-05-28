<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $productos = [
            // Pasteles
            [
                'nombre' => 'Pastel de Chocolate',
                'descripcion' => 'Delicioso pastel de chocolate con ganache',
                'descripcion_larga' => 'Nuestro pastel de chocolate es una obra maestra de la repostería, elaborado con el más fino chocolate negro y una técnica perfeccionada durante generaciones. Cada capa está cuidadosamente horneada para lograr una textura húmeda y esponjosa, cubierta con una generosa capa de ganache de chocolate que se derrite en tu boca.',
                'precio' => 29.99,
                'imagen' => 'pastel-chocolate.jpg',
                'ingredientes' => 'Chocolate, harina, huevos, azúcar, mantequilla, crema para ganache',
                'categoria_id' => 1,
                'stock' => 10
            ],
            [
                'nombre' => 'Pastel de Frutas',
                'descripcion' => 'Pastel decorado con frutas frescas',
                'descripcion_larga' => 'Un festín visual y delicioso, nuestro pastel de frutas combina la frescura de las frutas de temporada con la suavidad de nuestro bizcocho artesanal.',
                'precio' => 32.99,
                'imagen' => 'pastel-frutas.jpg',
                'ingredientes' => 'Harina, huevos, azúcar, mantequilla, frutas frescas, crema batida',
                'categoria_id' => 1,
                'stock' => 8
            ],
            [
                'nombre' => 'Pastel Tres Leches',
                'descripcion' => 'Clásico pastel de tres leches',
                'descripcion_larga' => 'Una receta tradicional llevada a la perfección, nuestro pastel de tres leches es una sinfonía de sabores y texturas.',
                'precio' => 27.99,
                'imagen' => 'pastel-tres-leches.jpg',
                'ingredientes' => 'Harina, huevos, azúcar, leche condensada, leche evaporada, crema',
                'categoria_id' => 1,
                'stock' => 12
            ],
            // Galletas
            [
                'nombre' => 'Galletas de Mantequilla',
                'descripcion' => 'Galletas caseras de mantequilla',
                'descripcion_larga' => 'Nuestras galletas de mantequilla son un tributo a la simplicidad y la excelencia.',
                'precio' => 8.99,
                'imagen' => 'galletas-mantequilla.jpg',
                'ingredientes' => 'Mantequilla, harina, azúcar, huevos, vainilla',
                'categoria_id' => 2,
                'stock' => 20
            ],
            [
                'nombre' => 'Galletas de Chocolate',
                'descripcion' => 'Galletas con chips de chocolate',
                'descripcion_larga' => 'Cada galleta es un tesoro de chocolate, repleta de generosos chips que crean explosiones de sabor en cada bocado.',
                'precio' => 9.99,
                'imagen' => 'galletas-chocolate.jpg',
                'ingredientes' => 'Harina, chocolate, mantequilla, azúcar, huevos',
                'categoria_id' => 2,
                'stock' => 15
            ],
            [
                'nombre' => 'Galletas de Avena',
                'descripcion' => 'Galletas saludables de avena',
                'descripcion_larga' => 'Nuestras galletas de avena son el equilibrio perfecto entre lo saludable y lo delicioso.',
                'precio' => 7.99,
                'imagen' => 'galletas-avena.jpg',
                'ingredientes' => 'Avena, harina, mantequilla, azúcar, pasas',
                'categoria_id' => 2,
                'stock' => 18
            ],
            // Postres
            [
                'nombre' => 'Flan de Caramelo',
                'descripcion' => 'Flan casero con caramelo',
                'descripcion_larga' => 'Un postre clásico reinventado con nuestra receta especial.',
                'precio' => 12.99,
                'imagen' => 'flan-caramelo.webp',
                'ingredientes' => 'Huevos, leche, azúcar, vainilla, caramelo',
                'categoria_id' => 3,
                'stock' => 10
            ],
            [
                'nombre' => 'Cheesecake',
                'descripcion' => 'Cheesecake de frutos rojos',
                'descripcion_larga' => 'Un cheesecake que es pura indulgencia.',
                'precio' => 18.99,
                'imagen' => 'cheescake.jpg',
                'ingredientes' => 'Queso crema, galletas, mantequilla, frutos rojos, azúcar',
                'categoria_id' => 3,
                'stock' => 6
            ],
            // Tartas
            [
                'nombre' => 'Tarta de Chocolate',
                'descripcion' => 'Deliciosa tarta de chocolate con ganache',
                'descripcion_larga' => 'Una tarta que combina la intensidad del mejor chocolate.',
                'precio' => 16.95,
                'imagen' => 'tarta-chocolate.avif',
                'ingredientes' => 'Chocolate, harina, huevos, azúcar, mantequilla',
                'categoria_id' => 4,
                'stock' => 10
            ],
            [
                'nombre' => 'Tarta de Frutas',
                'descripcion' => 'Tarta fresca con crema pastelera y frutas',
                'descripcion_larga' => 'Una creación que celebra los sabores naturales de las frutas de temporada.',
                'precio' => 14.95,
                'imagen' => 'tarta-frutas.jpg',
                'ingredientes' => 'Harina, frutas, crema pastelera, azúcar, gelatina',
                'categoria_id' => 4,
                'stock' => 8
            ],
            [
                'nombre' => 'Tarta de Queso',
                'descripcion' => 'Tarta de queso estilo New York',
                'descripcion_larga' => 'Nuestra interpretación del clásico cheesecake neoyorquino.',
                'precio' => 15.95,
                'imagen' => 'tarta-queso.jpg',
                'ingredientes' => 'Queso crema, huevos, azúcar, galletas, frutos rojos',
                'categoria_id' => 4,
                'stock' => 12
            ],
            // Donuts
            [
                'nombre' => 'Donut Glaseado',
                'descripcion' => 'Donut clásico con glaseado de azúcar',
                'descripcion_larga' => 'Nuestros donuts glaseados son el epítome de la simplicidad deliciosa.',
                'precio' => 2.50,
                'imagen' => 'donut-glaseado.jpg',
                'ingredientes' => 'Harina, azúcar, levadura, glaseado, aceite',
                'categoria_id' => 5,
                'stock' => 30
            ],
            [
                'nombre' => 'Donut de Chocolate',
                'descripcion' => 'Donut cubierto con chocolate y sprinkles',
                'descripcion_larga' => 'Un festín de chocolate para los verdaderos amantes del cacao.',
                'precio' => 2.95,
                'imagen' => 'donut-chocolate.webp',
                'ingredientes' => 'Harina, chocolate, sprinkles, azúcar, aceite',
                'categoria_id' => 5,
                'stock' => 25
            ],
            [
                'nombre' => 'Donut de Crema',
                'descripcion' => 'Donut relleno de crema pastelera',
                'descripcion_larga' => 'Una deliciosa sorpresa en cada bocado.',
                'precio' => 3.25,
                'imagen' => 'donut-crema.webp',
                'ingredientes' => 'Harina, crema pastelera, azúcar, aceite, vainilla',
                'categoria_id' => 5,
                'stock' => 20
            ],
            // Roles
            [
                'nombre' => 'Rol de Canela',
                'descripcion' => 'Rol de canela con glaseado',
                'descripcion_larga' => 'Un clásico reconfortante que llena el ambiente con su aroma a canela recién horneada.',
                'precio' => 4.95,
                'imagen' => 'rol-canela.jpg',
                'ingredientes' => 'Harina, canela, azúcar, crema de queso, mantequilla',
                'categoria_id' => 6,
                'stock' => 15
            ],
            [
                'nombre' => 'Rol de Chocolate',
                'descripcion' => 'Rol de chocolate con relleno de crema',
                'descripcion_larga' => 'Una versión chocolatosa de nuestro rol clásico.',
                'precio' => 5.95,
                'imagen' => 'rol-chocolate.webp',
                'ingredientes' => 'Harina, chocolate, crema, azúcar, mantequilla',
                'categoria_id' => 6,
                'stock' => 12
            ],
            [
                'nombre' => 'Rol de Pistacho',
                'descripcion' => 'Rol relleno de crema de pistacho',
                'descripcion_larga' => 'Una interpretación única con el delicado sabor del pistacho.',
                'precio' => 5.95,
                'imagen' => 'rol-pistacho.png',
                'ingredientes' => 'Harina, pistacho, crema, azúcar, mantequilla',
                'categoria_id' => 6,
                'stock' => 10
            ],
            // Pan
            [
                'nombre' => 'Pan de Masa Madre',
                'descripcion' => 'Pan artesanal de masa madre',
                'descripcion_larga' => 'Nuestro pan de masa madre es el resultado de un proceso de fermentación natural.',
                'precio' => 4.95,
                'imagen' => 'pan-masa-madre.jpeg',
                'ingredientes' => 'Harina, masa madre, agua, sal',
                'categoria_id' => 7,
                'stock' => 20
            ],
            [
                'nombre' => 'Pan de Centeno',
                'descripcion' => 'Pan de centeno con semillas',
                'descripcion_larga' => 'Un pan robusto y nutritivo elaborado con harina de centeno.',
                'precio' => 4.95,
                'imagen' => 'pan-centeno.webp',
                'ingredientes' => 'Harina de centeno, semillas, agua, sal',
                'categoria_id' => 7,
                'stock' => 15
            ],
            [
                'nombre' => 'Pan Integral',
                'descripcion' => 'Pan integral con granos enteros',
                'descripcion_larga' => 'Un pan saludable que no compromete el sabor.',
                'precio' => 3.95,
                'imagen' => 'pan-integral.jpg',
                'ingredientes' => 'Harina integral, granos, agua, sal',
                'categoria_id' => 7,
                'stock' => 18
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
