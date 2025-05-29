<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminPanelController extends Controller
{
    /**
     * Mostrar el panel de control de administración
     */
    public function dashboard()
    {
        // Estadísticas de pedidos
        $totalPedidos = Compra::count();
        $pedidosPendientes = Compra::where('estado', 'confirmado')->count();
        $pedidosEnProceso = Compra::where('estado', 'en_proceso')->count();
        $pedidosCompletados = Compra::where('estado', 'recogido')->count();

        // Estadísticas adicionales
        $totalProductos = Producto::count();
        $totalUsuarios = User::count();
        $totalCompras = $totalPedidos; // Ya lo tenemos de arriba

        // Ventas recientes (últimos 10 pedidos)
        $ventasRecientes = Compra::with(['user', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPedidos',
            'pedidosPendientes',
            'pedidosEnProceso',
            'pedidosCompletados',
            'totalProductos',
            'totalUsuarios',
            'totalCompras',
            'ventasRecientes'
        ));
    }

    public function actualizarEstadoPedido(Request $request, $id)
    {
        $pedido = Compra::findOrFail($id);
        $estadoAnterior = $pedido->estado;

        $request->validate([
            'estado' => 'required|string|in:confirmado,en_proceso,terminado,recogido,cancelado',
        ]);

        $pedido->estado = $request->estado;

        if ($request->has('comentario') && !empty($request->comentario)) {
            $pedido->notas = $pedido->notas
                ? $pedido->notas . "\n\n" . now()->format('d/m/Y H:i') . " - " . $request->comentario
                : now()->format('d/m/Y H:i') . " - " . $request->comentario;
        }

        $pedido->save();

        // Enviar correo electrónico según el estado
        try {
            if ($estadoAnterior != $request->estado) {
                // Enviar correo al cliente
                $this->enviarCorreoEstadoPedido($pedido);
            }
        } catch (\Exception $e) {
            // Registrar el error pero permitir que la actualización continúe
            Log::error('Error al enviar correo: ' . $e->getMessage());
        }

        return redirect()->route('admin.pedidos.ver', $pedido->codigo ?? $pedido->id)
            ->with('success', 'Estado del pedido actualizado correctamente');
    }

    /**
     * Eliminar un pedido
     */
    public function eliminarPedido($id)
    {
        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Buscar el pedido por código o ID
            $pedido = Compra::with(['detalles', 'detalles.reviews'])
                ->where('codigo', $id)
                ->orWhere('id', $id)
                ->firstOrFail();
            
            // Registrar información de depuración
            Log::info('Intentando eliminar pedido ID/Código: ' . $id);
            Log::info('Pedido encontrado - ID: ' . $pedido->id . ', Código: ' . $pedido->codigo);
            Log::info('Detalles encontrados: ' . $pedido->detalles->count());
            
            // Eliminar las reseñas asociadas a los detalles primero
            foreach ($pedido->detalles as $detalle) {
                $detalle->reviews()->delete();
            }

            // Eliminar los detalles
            $pedido->detalles()->delete();
            
            // Eliminar el pedido
            $pedido->delete();

            // Confirmar transacción
            DB::commit();

            return redirect()->route('admin.pedidos')
                ->with('success', 'Pedido eliminado correctamente');
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            
            // Registrar el error
            Log::error('Error al eliminar pedido: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.pedidos')
                ->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Enviar correo electrónico según el estado del pedido
     */
    protected function enviarCorreoEstadoPedido($pedido)
    {
        $estadosTraducidos = [
            'confirmado' => 'confirmado y en cola',
            'en_proceso' => 'en proceso de elaboración',
            'terminado' => 'terminado y listo para recoger',
            'recogido' => 'recogido',
            'cancelado' => 'cancelado'
        ];

        $estadoActual = $estadosTraducidos[$pedido->estado] ?? $pedido->estado;

        try {
            // Enviar correo al cliente
            Mail::send('emails.estado-pedido', [
                'pedido' => $pedido,
                'estadoActual' => $estadoActual
            ], function($message) use ($pedido) {
                $message->to($pedido->email)
                        ->subject('Actualización de tu pedido #' . $pedido->codigo . ' - Mi Sueño Dulce');
            });

            Log::info('Correo de actualización enviado al cliente: ' . $pedido->email);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de actualización: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mostrar la lista de pedidos
     */
    public function pedidos(Request $request)
    {
        $query = Compra::with(['user', 'detalles.producto'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtro por estado si existe
        if ($request->has('status') && $request->status != 'all') {
            $query->where('estado', $request->status);
        }

        // Aplicar búsqueda si existe
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $pedidos = $query->paginate(10)->withQueryString();
        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Ver detalles de un pedido específico
     */
    public function verPedido($id)
    {
        try {
            // Buscar el pedido por código o ID
            $pedido = Compra::where(function ($query) use ($id) {
                        $query->where('id', $id)
                              ->orWhere('codigo', $id);
                    })
                    ->with(['user', 'detalles.producto'])
                    ->firstOrFail();

            return view('admin.pedidos.ver', compact('pedido'));
        } catch (\Exception $e) {
            Log::error('Error al ver pedido: ' . $e->getMessage());
            return redirect()->route('admin.pedidos')
                ->with('error', 'No se pudo encontrar el pedido especificado.');
        }
    }
} 