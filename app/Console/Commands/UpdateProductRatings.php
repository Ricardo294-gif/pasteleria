<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\Review;

class UpdateProductRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-product-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza la calificación promedio de todos los productos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando actualización de calificaciones...');
        
        $productos = Producto::all();
        $contador = 0;
        
        $this->output->progressStart(count($productos));
        
        foreach ($productos as $producto) {
            $avgRating = Review::where('producto_id', $producto->id)
                ->where('is_approved', true)
                ->avg('rating');
                
            $producto->calificacion = $avgRating ?? 0;
            $producto->save();
            
            $contador++;
            $this->output->progressAdvance();
        }
        
        $this->output->progressFinish();
        
        $this->info("¡Completado! Se actualizaron las calificaciones de $contador productos.");
    }
}
