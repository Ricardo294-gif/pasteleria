<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Producto;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Muestra el dashboard de administración
     */
    public function dashboard()
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Verificar si el usuario es administrador
        if (Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $totalUsuarios = User::count();
        $totalProductos = Producto::count();
        $totalCompras = Compra::count();
        $ventasRecientes = Compra::with(['detalles.producto', 'user'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact('totalUsuarios', 'totalProductos', 'totalCompras', 'ventasRecientes'));
    }
}
