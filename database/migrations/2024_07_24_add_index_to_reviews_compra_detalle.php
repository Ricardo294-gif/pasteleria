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
        Schema::table('reviews', function (Blueprint $table) {
            // Agregar un índice único en compra_detalle_id para asegurar que
            // una compra solo pueda tener una reseña asociada
            $table->unique('compra_detalle_id', 'reviews_compra_detalle_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_compra_detalle_id_unique');
        });
    }
};
