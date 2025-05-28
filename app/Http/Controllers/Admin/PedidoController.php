<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Muestra el listado de pedidos
     */
    public function index(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $query = Compra::with('user')->orderBy('created_at', 'desc');
        
        // Aplicar filtro por estado si existe
        if ($request->has('status') && $request->status != 'all') {
            $query->where('estado', $request->status);
        }

        $pedidos = $query->paginate(10)->withQueryString();
        
        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Ver detalles de un pedido
     */
    public function ver($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $pedido = Compra::with(['detalles.producto', 'user'])->findOrFail($id);
        return view('admin.pedidos.ver', compact('pedido'));
    }

    /**
     * Actualizar el estado de un pedido
     */
    public function actualizarEstado(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $pedido = Compra::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:pendiente,pagado,enviado,cancelado',
            'comentario' => 'nullable|string',
        ]);

        $pedido->estado = $request->estado;
        $pedido->save();

        return redirect()->route('admin.pedidos.ver', $pedido->id)
            ->with('success', 'Estado del pedido actualizado correctamente');
    }
}
