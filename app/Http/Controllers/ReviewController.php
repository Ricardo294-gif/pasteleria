<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Mostrar reseñas de un producto específico
     */
    public function index($productoId)
    {
        $producto = Producto::findOrFail($productoId);
        $reviews = $producto->reviews()->where('is_approved', true)
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

        return view('reviews.index', compact('producto', 'reviews'));
    }

    /**
     * Mostrar formulario para crear una reseña
     */
    public function create($id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                            ->with('error', 'Debes iniciar sesión para escribir una reseña.');
        }

        $producto = Producto::findOrFail($id);

        // Comprobar si ya existe una reseña para este producto
        $existingReview = Review::where('user_id', Auth::id())
                               ->where('producto_id', $id)
                               ->first();
                               
        if ($existingReview) {
            // Ya no redirigimos a la página de edición, sino a la página del producto con un mensaje
            return redirect()->route('producto.detalle', $producto->id)
                ->with('info', 'Ya has escrito una reseña para este producto. Puedes editarla desde la sección de reseñas.');
        }
        
        // Primero verificar si el usuario tiene alguna compra de este producto (en cualquier estado)
        $compraPendiente = DB::table('compra_detalles')
            ->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
            ->where('compras.user_id', Auth::id())
            ->where('compra_detalles.producto_id', $id)
            ->whereNotIn('compras.estado', ['entregado', 'recogido'])
            ->first();
            
        if ($compraPendiente) {
            return redirect()->route('producto.detalle', $producto->id)
                ->with('error', 'No puedes dejar una reseña hasta que tu pedido haya sido entregado o recogido.');
        }

        // Verificar si el usuario ha comprado el producto y está completado
        $compraCompletada = DB::table('compra_detalles')
            ->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
            ->where('compras.user_id', Auth::id())
            ->where('compra_detalles.producto_id', $id)
            ->whereIn('compras.estado', ['entregado', 'recogido'])
            ->first();

        if (!$compraCompletada) {
            return redirect()->route('producto.detalle', $producto->id)
                ->with('error', 'Debes comprar este producto antes de poder dejar una reseña.');
        }

        return view('reviews.create', compact('producto'));
    }

    /**
     * Almacenar una nueva reseña
     */
    public function store(Request $request, $id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Debes iniciar sesión para escribir una reseña.'], 401);
            }
            return redirect()->route('login')
                            ->with('error', 'Debes iniciar sesión para escribir una reseña.');
        }

        $producto = Producto::findOrFail($id);

        // Comprobar si ya existe una reseña para este producto
        $existingReview = Review::where('user_id', Auth::id())
                               ->where('producto_id', $id)
                               ->first();
                               
        if ($existingReview) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Ya has escrito una reseña para este producto.'], 403);
            }
            return redirect()->route('producto.detalle', $producto->id)
                ->with('info', 'Ya has escrito una reseña para este producto. Puedes editarla desde la sección de reseñas.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Primero verificar si el usuario tiene alguna compra de este producto pendiente
        $compraPendiente = DB::table('compra_detalles')
            ->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
            ->where('compras.user_id', Auth::id())
            ->where('compra_detalles.producto_id', $id)
            ->whereNotIn('compras.estado', ['entregado', 'recogido'])
            ->first();
            
        if ($compraPendiente) {
            if ($request->ajax()) {
                return response()->json(['error' => 'No puedes dejar una reseña hasta que tu pedido haya sido entregado o recogido.'], 403);
            }
            return redirect()->route('producto.detalle', $producto->id)
                ->with('error', 'No puedes dejar una reseña hasta que tu pedido haya sido entregado o recogido.');
        }

        // Verificar si el usuario ha comprado el producto y está completado
        $compraCompletada = DB::table('compra_detalles')
            ->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
            ->where('compras.user_id', Auth::id())
            ->where('compra_detalles.producto_id', $id)
            ->whereIn('compras.estado', ['entregado', 'recogido'])
            ->select('compra_detalles.id')
            ->first();

        if (!$compraCompletada) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Debes comprar este producto antes de poder dejar una reseña.'], 403);
            }
            return redirect()->route('producto.detalle', $producto->id)
                ->with('error', 'Debes comprar este producto antes de poder dejar una reseña.');
        }

        try {
            // Guardar la información detallada del error
            Log::info('Intentando crear reseña para usuario ID: ' . Auth::id() . ', producto ID: ' . $id . ', compra_detalle_id: ' . $compraCompletada->id);

            // Usar una transacción para evitar problemas de integridad
            $reviewId = DB::transaction(function () use ($compraCompletada, $id, $validated) {
                // Crear el objeto Review y guardarlo directamente
                $review = new Review([
                    'user_id' => Auth::id(),
                    'producto_id' => $id,
                    'compra_detalle_id' => $compraCompletada->id,
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'],
                    'is_approved' => true
                ]);

                $review->save();
                return $review->id;
            });

            // Obtener la reseña recién creada
            $review = Review::findOrFail($reviewId);

            Log::info('Nueva reseña creada con éxito con ID ' . $reviewId);

            if ($request->ajax()) {
                $reviewData = $review->toArray();
                $reviewData['user_name'] = Auth::user()->name . ' ' . Auth::user()->apellido;
                return response()->json([
                    'success' => 'Reseña publicada correctamente.',
                    'review' => $reviewData
                ]);
            }

            return redirect()->route('producto.detalle', $producto->id)
                            ->with('success', 'Tu reseña ha sido publicada. ¡Gracias por compartir tu opinión!');
        } catch (\Exception $e) {
            Log::error('Error al guardar reseña: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Ha ocurrido un error al guardar la reseña. Por favor, inténtalo de nuevo.',
                    'details' => env('APP_DEBUG') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->route('producto.detalle', $producto->id)
                ->with('error', 'Ha ocurrido un error al guardar la reseña. Por favor, inténtalo de nuevo.');
        }
    }
    
    /**
     * Mostrar formulario para editar una reseña
     */
    public function edit($id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                            ->with('error', 'Debes iniciar sesión para editar una reseña.');
        }
        
        $review = Review::findOrFail($id);
        
        // Verificar si el usuario es el dueño de la reseña
        if (Auth::id() != $review->user_id) {
            return redirect()->route('producto.detalle', $review->producto_id)
                ->with('error', 'No tienes permiso para editar esta reseña.');
        }
        
        $producto = $review->producto;
        
        return view('reviews.edit', compact('review', 'producto'));
    }
    
    /**
     * Actualizar una reseña existente
     */
    public function update(Request $request, $id)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            Log::warning('Intento de actualizar reseña sin autenticación - ID: ' . $id);
            if ($request->ajax()) {
                return response()->json(['error' => 'Debes iniciar sesión para editar una reseña.'], 401);
            }
            return redirect()->route('login')
                            ->with('error', 'Debes iniciar sesión para editar una reseña.');
        }
        
        // Log de inicio de actualización
        Log::info('Actualizando reseña ID: ' . $id . ' por usuario ID: ' . Auth::id());
        
        $review = Review::findOrFail($id);
        
        // Verificar si el usuario es el dueño de la reseña
        if (Auth::id() != $review->user_id) {
            Log::warning('Usuario ID ' . Auth::id() . ' intentó editar reseña ID ' . $id . ' que no le pertenece');
            if ($request->ajax()) {
                return response()->json(['error' => 'No tienes permiso para editar esta reseña.'], 403);
            }
            return redirect()->route('producto.detalle', $review->producto_id)
                ->with('error', 'No tienes permiso para editar esta reseña.');
        }
        
        // Log de validación
        Log::info('Validando datos de la reseña ID: ' . $id);
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);
        
        Log::info('Datos validados para reseña ID: ' . $id . ' - Rating: ' . $validated['rating']);
        
        try {
            $review->rating = $validated['rating'];
            $review->comment = $validated['comment'];
            $review->save();
            
            Log::info('Reseña ID ' . $id . ' actualizada correctamente por el usuario ID ' . Auth::id());
            
            if ($request->ajax()) {
                $reviewData = $review->toArray();
                $reviewData['user_name'] = Auth::user()->name . ' ' . Auth::user()->apellido;
                return response()->json([
                    'success' => 'Reseña actualizada correctamente.',
                    'review' => $reviewData
                ]);
            }
            
            return redirect()->route('producto.detalle', $review->producto_id)
                            ->with('success', 'Tu reseña ha sido actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar reseña: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Ha ocurrido un error al actualizar la reseña. Por favor, inténtalo de nuevo.',
                    'details' => env('APP_DEBUG') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->route('reviews.edit', $id)
                ->with('error', 'Ha ocurrido un error al actualizar la reseña. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Obtener estadísticas de reseñas de un producto
     */
    public function getStats($id)
    {
        $producto = Producto::findOrFail($id);

        $count = $producto->reviews()->where('is_approved', true)->count();
        $average = $producto->getAverageRatingAttribute();

        return response()->json([
            'count' => $count,
            'average' => $average
        ]);
    }

    /**
     * Obtener estadísticas del usuario para un producto específico
     */
    public function getUserStats($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $producto = Producto::findOrFail($id);
        
        // Comprobar si ya existe una reseña para este producto
        $existingReview = Review::where('user_id', Auth::id())
                               ->where('producto_id', $id)
                               ->first();

        // Contar el total de compras completadas
        $comprasCompletadas = DB::table('compra_detalles')
            ->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
            ->where('compras.user_id', Auth::id())
            ->where('compra_detalles.producto_id', $id)
            ->whereIn('compras.estado', ['entregado', 'recogido'])
            ->count();

        // Contar compras pendientes (no entregadas/recogidas)
        $comprasPendientes = DB::table('compra_detalles')
            ->join('compras', 'compra_detalles.compra_id', '=', 'compras.id')
            ->where('compras.user_id', Auth::id())
            ->where('compra_detalles.producto_id', $id)
            ->whereNotIn('compras.estado', ['entregado', 'recogido', 'cancelado'])
            ->count();

        return response()->json([
            'compras_completadas' => $comprasCompletadas,
            'compras_pendientes' => $comprasPendientes,
            'ha_comprado_producto' => $comprasCompletadas > 0,
            'tiene_pedidos_pendientes' => $comprasPendientes > 0,
            'tiene_resena' => $existingReview ? true : false,
            'review_id' => $existingReview ? $existingReview->id : null
        ]);
    }

    /**
     * Eliminar una reseña
     */
    public function destroy($id)
    {
        // Verificación de autenticación
        if (!Auth::check()) {
            return redirect()->route('login')
                            ->with('error', 'Debes iniciar sesión para eliminar una reseña.');
        }

        // Obtener la reseña
        $review = Review::findOrFail($id);
        $productoId = $review->producto_id;

        // Verificar permisos
        if (Auth::id() != $review->user_id && !Auth::user()->is_admin) {
            return redirect()->route('producto.detalle', $productoId)
                ->with('error', 'No tienes permiso para eliminar esta reseña.');
        }

        // Eliminar la reseña
        $review->delete();

        // Registro básico
        Log::info('Reseña ID ' . $id . ' eliminada por usuario ID ' . Auth::id());

        // Si se ha proporcionado una URL de redirección, usarla
        if (request()->has('redirect_url')) {
            return redirect(request()->input('redirect_url'))
                ->with('success', 'La reseña ha sido eliminada correctamente.');
        }

        // Redirección a la página del producto (en caso de que no haya URL específica)
        return redirect()->route('producto.detalle', $productoId)
            ->with('success', 'La reseña ha sido eliminada correctamente.');
    }
}

