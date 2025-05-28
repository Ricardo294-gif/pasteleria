<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Review;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige reseñas mal asignadas a productos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando problemas en reseñas...');
        
        // 1. Detectar reseñas huérfanas (sin producto)
        $orphanReviews = Review::whereNotIn('producto_id', function($query) {
            $query->select('id')->from('productos');
        })->get();
        
        if ($orphanReviews->count() > 0) {
            $this->warn("Se encontraron {$orphanReviews->count()} reseñas sin producto asociado válido.");
            
            foreach ($orphanReviews as $review) {
                $this->warn("Reseña ID: {$review->id} con producto_id inválido: {$review->producto_id} - Usuario: {$review->user_id}");
                
                if ($this->confirm("¿Desea eliminar esta reseña?")) {
                    $review->delete();
                    $this->info("Reseña ID: {$review->id} eliminada.");
                }
            }
        } else {
            $this->info("No se encontraron reseñas huérfanas.");
        }
        
        // 2. Detectar usuarios con múltiples reseñas para el mismo producto
        $duplicateReviews = DB::select("
            SELECT user_id, producto_id, COUNT(*) as count 
            FROM reviews 
            GROUP BY user_id, producto_id 
            HAVING COUNT(*) > 1
        ");
        
        if (count($duplicateReviews) > 0) {
            $this->warn("Se encontraron " . count($duplicateReviews) . " casos de usuarios con múltiples reseñas para el mismo producto.");
            
            foreach ($duplicateReviews as $dup) {
                $reviews = Review::where('user_id', $dup->user_id)
                            ->where('producto_id', $dup->producto_id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                
                $producto = Producto::find($dup->producto_id);
                $nombreProducto = $producto ? $producto->nombre : "Producto desconocido";
                
                $this->warn("Usuario ID: {$dup->user_id} tiene {$dup->count} reseñas para producto: {$nombreProducto} (ID: {$dup->producto_id})");
                
                // Mantener solo la reseña más reciente
                if ($this->confirm("¿Desea conservar solo la reseña más reciente y eliminar las demás?")) {
                    $keepFirst = true;
                    foreach ($reviews as $review) {
                        if ($keepFirst) {
                            $this->info("Manteniendo reseña ID: {$review->id} - Fecha: {$review->created_at}");
                            $keepFirst = false;
                        } else {
                            $review->delete();
                            $this->info("Eliminando reseña duplicada ID: {$review->id} - Fecha: {$review->created_at}");
                        }
                    }
                }
            }
        } else {
            $this->info("No se encontraron usuarios con múltiples reseñas para el mismo producto.");
        }
        
        $this->info('Proceso completado.');
        return Command::SUCCESS;
    }
} 