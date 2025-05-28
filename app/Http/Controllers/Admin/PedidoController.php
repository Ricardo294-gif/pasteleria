<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * Eliminar un pedido
     */
    public function eliminar($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        try {
            // Iniciar transacción
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Buscar el pedido por código o ID
            $pedido = Compra::with(['detalles', 'detalles.reviews'])
                ->where('codigo', $id)
                ->orWhere('id', $id)
                ->firstOrFail();
            
            // Registrar información de depuración
            \Illuminate\Support\Facades\Log::info('Intentando eliminar pedido ID/Código: ' . $id);
            \Illuminate\Support\Facades\Log::info('Pedido encontrado - ID: ' . $pedido->id . ', Código: ' . $pedido->codigo);
            \Illuminate\Support\Facades\Log::info('Detalles encontrados: ' . $pedido->detalles->count());
            
            // Eliminar las reseñas asociadas a los detalles primero
            foreach ($pedido->detalles as $detalle) {
                $detalle->reviews()->delete();
            }

            // Eliminar los detalles
            $pedido->detalles()->delete();
            
            // Eliminar el pedido
            $pedido->delete();

            // Confirmar transacción
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.pedidos')
                ->with('success', 'Pedido eliminado correctamente');
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            \Illuminate\Support\Facades\DB::rollBack();
            
            // Registrar el error
            \Illuminate\Support\Facades\Log::error('Error al eliminar pedido: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.pedidos')
                ->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }
}
