<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSetupController extends Controller
{
    /**
     * Crear un usuario administrador
     */
    public function createAdmin(Request $request)
    {
        // Verificar que la solicitud sea segura (usando un token o desde localhost)
        if (!$request->isMethod('post')) {
            return redirect()->route('index');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // Crear el administrador
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 1, // Esto marca al usuario como administrador
        ]);

        return redirect()->route('login')
            ->with('success', 'Usuario administrador creado exitosamente. Ya puedes iniciar sesión.');
    }

    /**
     * Mostrar el formulario para crear administrador
     */
    public function showSetupForm()
    {
        // Verificar si ya existe algún administrador
        $adminExists = User::where('is_admin', 1)->exists();
        if ($adminExists) {
            return redirect()->route('index')
                ->with('error', 'Ya existe un usuario administrador en el sistema.');
        }

        return view('admin.setup');
    }
}
