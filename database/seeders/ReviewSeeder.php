<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Producto;
use App\Models\User;
use App\Models\CompraDetalle;
use Illuminate\Support\Facades\Log;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Producto::all();
        $users = User::where('is_admin', 0)->get();

        // Comentarios positivos y negativos predefinidos
        $comentariosPositivos = [
            "¡Excelente producto! Superó todas mis expectativas.",
            "La calidad es increíble, definitivamente volveré a comprar.",
            "Uno de los mejores productos que he probado, muy recomendable.",
            "Me encantó, el sabor es espectacular.",
            "Perfecto en todos los aspectos, no cambiaría nada.",
            "La relación calidad-precio es excelente.",
            "Muy satisfecho con mi compra, llegó en perfecto estado.",
            "El sabor es único, no he probado nada igual.",
            "La presentación es impecable, se nota la calidad.",
            "Una experiencia gastronómica excepcional."
        ];

        $comentariosNegativos = [
            "No cumplió con mis expectativas, esperaba más.",
            "La calidad podría mejorar significativamente.",
            "El precio es demasiado alto para lo que ofrece.",
            "No me convenció del todo, hay mejores opciones.",
            "Regular, esperaba algo mejor.",
            "Tiene aspectos que podrían mejorar.",
            "No es tan bueno como esperaba.",
            "La relación calidad-precio no es la mejor.",
            "Hay margen de mejora en varios aspectos.",
            "No estoy seguro de volver a comprarlo."
        ];

        foreach ($productos as $producto) {
            Log::info("Generando reseñas para el producto ID: " . $producto->id);

            // Obtener pedidos completados de este producto
            $pedidosCompletados = CompraDetalle::whereHas('compra', function($query) {
                $query->whereIn('estado', ['recogido', 'terminado']);
            })->where('producto_id', $producto->id)->get();

            $numPedidosCompletados = $pedidosCompletados->count();
            Log::info("Pedidos completados encontrados: " . $numPedidosCompletados);

            // Si no hay suficientes pedidos, crear menos reseñas
            $numReseñasACrear = min(10, $numPedidosCompletados);
            
            if ($numReseñasACrear == 0) {
                Log::warning("No hay pedidos completados para el producto ID: " . $producto->id);
                continue;
            }

            Log::info("Creando " . $numReseñasACrear . " reseñas para el producto ID: " . $producto->id);

            // Crear las reseñas
            for ($i = 0; $i < $numReseñasACrear; $i++) {
                $rating = rand(1, 5); // Valoración aleatoria de 1 a 5
                $pedido = $pedidosCompletados->random();
                $user = $users->random();

                // Seleccionar comentario basado en la valoración
                $comentario = $rating >= 4 
                    ? $comentariosPositivos[array_rand($comentariosPositivos)]
                    : $comentariosNegativos[array_rand($comentariosNegativos)];

                try {
                    Review::create([
                        'user_id' => $user->id,
                        'producto_id' => $producto->id,
                        'compra_detalle_id' => $pedido->id,
                        'rating' => $rating,
                        'comment' => $comentario,
                        'is_approved' => true,
                        'created_at' => $pedido->compra->created_at->addDays(rand(1, 5)) // Reseña entre 1 y 5 días después de la compra
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error al crear reseña: " . $e->getMessage());
                    continue;
                }
            }

            // Actualizar la calificación promedio del producto
            $producto->calificacion = $producto->reviews()->avg('rating');
            $producto->save();
            
            Log::info("Reseñas creadas y calificación actualizada para el producto ID: " . $producto->id);
        }
    }
} 