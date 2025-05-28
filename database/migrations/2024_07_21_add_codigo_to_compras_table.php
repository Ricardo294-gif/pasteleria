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
        Schema::table('compras', function (Blueprint $table) {
            $table->string('codigo', 10)->nullable()->after('id');
        });

        // Actualizar registros existentes con códigos aleatorios
        $compras = DB::table('compras')->get();
        foreach ($compras as $compra) {
            DB::table('compras')
                ->where('id', $compra->id)
                ->update(['codigo' => $this->generarCodigoAleatorio()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }

    /**
     * Genera un código aleatorio alfanumérico de 10 caracteres
     */
    private function generarCodigoAleatorio(): string
    {
        return strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
    }
};
