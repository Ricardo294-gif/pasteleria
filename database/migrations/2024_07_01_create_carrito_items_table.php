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
        if (!Schema::hasTable('carrito_items')) {
            Schema::create('carrito_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('carrito_id');
                $table->unsignedBigInteger('producto_id');
                $table->integer('cantidad')->default(1);
                $table->decimal('precio_unitario', 10, 2);
                $table->timestamps();

                // Índices para mejorar el rendimiento
                $table->index('carrito_id');
                $table->index('producto_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrito_items');
    }
};
