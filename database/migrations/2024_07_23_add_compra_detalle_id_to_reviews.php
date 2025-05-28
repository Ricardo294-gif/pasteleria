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
        // Comprobar primero si la columna ya existe
        if (!Schema::hasColumn('reviews', 'compra_detalle_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->foreignId('compra_detalle_id')->nullable()->after('producto_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'compra_detalle_id')) {
                $table->dropColumn('compra_detalle_id');
            }
        });
    }
};
