<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserEliminadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Comprobar si ya existe un usuario eliminado
        $usuarioEliminado = User::where('email', 'usuario_eliminado@sistema.com')->first();

        if (!$usuarioEliminado) {
            // Crear un usuario especial que representará a los usuarios eliminados
            User::create([
                'name' => 'Usuario',
                'apellido' => 'Eliminado',
                'email' => 'usuario_eliminado@sistema.com',
                'password' => Hash::make(bin2hex(random_bytes(20))), // Contraseña aleatoria muy compleja
                'is_admin' => 0,
            ]);

            $this->command->info('Usuario eliminado creado correctamente');
        } else {
            $this->command->info('El usuario eliminado ya existe');
        }
    }
}
