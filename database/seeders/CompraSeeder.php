<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Str;

class CompraSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Producto::all();
        $users = User::where('is_admin', 0)->get();
        $estados = ['pendiente', 'confirmado', 'en_proceso', 'terminado', 'recogido', 'cancelado'];
        $metodosPago = ['tarjeta', 'paypal', 'bizum', 'transferencia'];

        foreach ($productos as $producto) {
            // Primero crear 5 pedidos completados (para asegurar reseñas)
            for ($i = 0; $i < 5; $i++) {
                $user = $users->random();
                $estado = rand(0, 1) ? 'terminado' : 'recogido'; // Solo estados completados
                $metodoPago = $metodosPago[array_rand($metodosPago)];
                
                // Generar un código único más corto para el pedido
                $codigo = 'P' . strtoupper(Str::random(5));
                
                // Calcular el total
                $cantidad = rand(1, 3);
                $total = $producto->precio * $cantidad;

                // Crear el pedido completado
                $compra = Compra::create([
                    'user_id' => $user->id,
                    'codigo' => $codigo,
                    'nombre' => $user->name . ' ' . $user->apellido,
                    'email' => $user->email,
                    'telefono' => $user->telefono,
                    'metodo_pago' => $metodoPago,
                    'estado' => $estado,
                    'total' => $total,
                    'created_at' => now()->subDays(rand(5, 30)) // Pedidos más antiguos para las reseñas
                ]);

                // Crear el detalle del pedido
                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'nombre_producto' => $producto->nombre,
                    'precio_unitario' => $producto->precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $total,
                    'tamano' => ['normal', 'grande', 'muygrande'][array_rand(['normal', 'grande', 'muygrande'])]
                ]);
            }

            // Luego crear 5 pedidos con estados aleatorios
            for ($i = 0; $i < 5; $i++) {
                $user = $users->random();
                $estado = $estados[array_rand($estados)];
                $metodoPago = $metodosPago[array_rand($metodosPago)];
                
                $codigo = 'P' . strtoupper(Str::random(5));
                
                $cantidad = rand(1, 3);
                $total = $producto->precio * $cantidad;

                // Crear el pedido con estado aleatorio
                $compra = Compra::create([
                    'user_id' => $user->id,
                    'codigo' => $codigo,
                    'nombre' => $user->name . ' ' . $user->apellido,
                    'email' => $user->email,
                    'telefono' => $user->telefono,
                    'metodo_pago' => $metodoPago,
                    'estado' => $estado,
                    'total' => $total,
                    'created_at' => now()->subDays(rand(1, 10)) // Pedidos más recientes
                ]);

                // Crear el detalle del pedido
                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'nombre_producto' => $producto->nombre,
                    'precio_unitario' => $producto->precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $total,
                    'tamano' => ['normal', 'grande', 'muygrande'][array_rand(['normal', 'grande', 'muygrande'])]
                ]);
            }
        }
    }
} 