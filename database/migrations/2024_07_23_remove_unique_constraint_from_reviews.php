<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // No vamos a eliminar el índice único ya que está siendo usado
        // y ahora estamos usando compra_detalle_id para asegurar
        // que un usuario solo pueda dejar una reseña por compra
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay nada que revertir ya que no hicimos cambios
    }
};
