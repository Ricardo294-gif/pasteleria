<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('review_purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->foreignId('compra_detalle_id')->constrained('compra_detalles')->onDelete('cascade');
            $table->timestamps();

            // Asegurar que una reseña solo esté asociada a una compra específica
            $table->unique(['review_id', 'compra_detalle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_purchase');
    }
};
