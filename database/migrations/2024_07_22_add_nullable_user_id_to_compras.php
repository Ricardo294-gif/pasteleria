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
        // Ejecutar una sentencia SQL directa para modificar la restricción de la clave foránea
        DB::statement('ALTER TABLE compras DROP FOREIGN KEY compras_user_id_foreign');

        // Modificar la columna user_id para que acepte valores nulos
        DB::statement('ALTER TABLE compras MODIFY user_id BIGINT UNSIGNED NULL');

        // Volver a crear la clave foránea pero con ON DELETE SET NULL
        DB::statement('ALTER TABLE compras ADD CONSTRAINT compras_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios
        DB::statement('ALTER TABLE compras DROP FOREIGN KEY compras_user_id_foreign');
        DB::statement('ALTER TABLE compras MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE compras ADD CONSTRAINT compras_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
