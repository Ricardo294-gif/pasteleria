<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ana',
                'apellido' => 'García Martínez',
                'email' => 'ana.garcia@example.com',
                'telefono' => '612345678',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Carlos',
                'apellido' => 'Rodríguez López',
                'email' => 'carlos.rodriguez@example.com',
                'telefono' => '623456789',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'María',
                'apellido' => 'Fernández Sánchez',
                'email' => 'maria.fernandez@example.com',
                'telefono' => '634567890',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'David',
                'apellido' => 'López González',
                'email' => 'david.lopez@example.com',
                'telefono' => '645678901',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Laura',
                'apellido' => 'Martínez Pérez',
                'email' => 'laura.martinez@example.com',
                'telefono' => '656789012',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Javier',
                'apellido' => 'Sánchez Ruiz',
                'email' => 'javier.sanchez@example.com',
                'telefono' => '667890123',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Carmen',
                'apellido' => 'González Torres',
                'email' => 'carmen.gonzalez@example.com',
                'telefono' => '678901234',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Miguel',
                'apellido' => 'Pérez Navarro',
                'email' => 'miguel.perez@example.com',
                'telefono' => '689012345',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Isabel',
                'apellido' => 'Torres Moreno',
                'email' => 'isabel.torres@example.com',
                'telefono' => '690123456',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ],
            [
                'name' => 'Pablo',
                'apellido' => 'Ruiz Jiménez',
                'email' => 'pablo.ruiz@example.com',
                'telefono' => '601234567',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
} 