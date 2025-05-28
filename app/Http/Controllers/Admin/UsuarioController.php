<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra el listado de usuarios
     */
    public function index(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $usuarios = User::query();

        // Búsqueda por nombre, email o teléfono
        if ($request->has('search')) {
            $search = $request->search;
            $usuarios->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        $usuarios = $usuarios->paginate(10)->withQueryString();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para crear usuario
     */
    public function crear()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        return view('admin.usuarios.crear');
    }

    /**
     * Guardar nuevo usuario
     */
    public function guardar(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        $usuario = User::create([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'is_admin' => $request->has('is_admin') ? 1 : 0,
        ]);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function editar($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $usuario = User::findOrFail($id);
        return view('admin.usuarios.editar', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function actualizar(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'telefono' => 'nullable|string|max:20',
        ]);

        $usuario->name = $request->name;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        if ($request->has('is_admin')) {
            $usuario->is_admin = 1;
        } else {
            $usuario->is_admin = 0;
        }

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Eliminar usuario
     */
    public function eliminar($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $usuario = User::findOrFail($id);

        // Evitar que un admin se elimine a sí mismo
        if ($usuario->id === Auth::id()) {
            return redirect()->route('admin.usuarios')->with('error', 'No puedes eliminar tu propio usuario');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente');
    }
}
