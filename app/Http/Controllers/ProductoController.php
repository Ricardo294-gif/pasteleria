<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function detalle($id)
    {
        try {
            // Forzar recarga desde la base de datos sin caché
            $producto = Producto::where('id', $id)
                ->with(['reviews' => function($query) {
                    $query->where('is_approved', true)
                        ->orderBy('created_at', 'desc');
                }])
                ->firstOrFail();

            // Registrar en el log la descripción larga para depuración
            Log::info('Detalle de producto cargado: ' . $producto->id . ' - Descripción larga: ' . $producto->descripcion_larga);
            
            // Buscar productos relacionados de la misma categoría
        $productosRelacionados = [];
            
            if ($producto->categoria_id) {
                // Obtener productos de la misma categoría excepto el actual
                $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
                                                ->where('id', '!=', $producto->id)
                                                ->take(4)
                                                ->get();
        }
        
        // Log de acceso al producto
            Log::info('Producto visualizado: ' . $producto->nombre);
        
        return view('producto', compact('producto', 'productosRelacionados'));
        } catch (\Exception $e) {
            Log::error('Error al mostrar detalle de producto: ' . $e->getMessage());
            return redirect()->route('index')->with('error', 'Producto no encontrado');
        }
    }

    public function agregarAlCarrito(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1|max:10'
        ]);

        // Aquí irá la lógica para agregar al carrito
        // Por ahora solo redirigimos con un mensaje de éxito
        return redirect()->back()->with('success', 'Producto agregado al carrito correctamente');
    }

    public function crear(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcion_larga' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'ingredientes' => 'required|string',
            'categoria' => 'nullable|string|max:50',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Subir la imagen
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
        }

        // Crear el producto
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->descripcion_larga = $request->descripcion_larga;
        $producto->precio = $request->precio;
        $producto->ingredientes = $request->ingredientes;
        $producto->categoria = $request->categoria;
        $producto->imagen = $imagenPath;
        $producto->save();

        return redirect()->route('admin.productos')->with('success', 'Producto creado correctamente.');
    }

    public function editar($id)
    {
        $producto = Producto::findOrFail($id);
        return view('admin.editar_producto', compact('producto'));
    }

    public function actualizar(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcion_larga' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'ingredientes' => 'required|string',
            'categoria' => 'nullable|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Obtener el producto
        $producto = Producto::findOrFail($id);

        // Actualizar los datos
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->descripcion_larga = $request->descripcion_larga;
        $producto->precio = $request->precio;
        $producto->ingredientes = $request->ingredientes;
        $producto->categoria = $request->categoria;

        // Actualizar la imagen si se proporciona una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen antigua
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            // Subir la nueva imagen
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagenPath;
        }

        $producto->save();

        return redirect()->route('admin.productos')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Lista todos los productos agrupados por categoría para la API
     */
    public function listarProductos()
    {
        try {
            // Obtener todas las categorías con sus productos
            $categorias = Categoria::with('productos')->get();
            
            // Crear la estructura similar al JSON
            $data = ['categorias' => []];
            
            foreach ($categorias as $categoria) {
                $productos = [];
                
                foreach ($categoria->productos as $producto) {
                    // Solo incluir productos que existen en la base de datos
                    $productos[] = [
                        'id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'descripcion' => $producto->descripcion,
                        'precio' => (float)$producto->precio,
                        'imagen' => $producto->imagen,
                        'ingredientes' => $producto->ingredientes
                    ];
                }
                
                // Solo agregar categorías que tengan productos
                if (count($productos) > 0) {
                    $data['categorias'][$categoria->nombre] = [
                        'productos' => $productos
                    ];
                }
            }
            
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error al listar productos para API: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar los productos'], 500);
        }
    }
    
    /**
     * Carga más reseñas para un producto específico (paginación)
     */
    public function cargarMasResenas(Request $request, $productoId)
    {
        $request->validate([
            'pagina' => 'required|integer|min:1',
            'porPagina' => 'required|integer|min:1|max:20',
            'rating' => 'nullable|string'
        ]);
        
        // Obtener los parámetros de paginación
        $pagina = $request->input('pagina');
        $porPagina = $request->input('porPagina', 5);
        $offset = ($pagina - 1) * $porPagina;
        $rating = $request->input('rating');
        
        // Buscar el producto
        $producto = Producto::findOrFail($productoId);
        
        // Construir la consulta base
        $query = $producto->reviews()
                        ->where('is_approved', true)
                        ->orderBy('created_at', 'desc');
            
        // Filtrar por rating si se especifica
        if ($rating && $rating !== 'all') {
            $query->where('rating', $rating);
        }
        
        // Obtener el total de reseñas con este filtro
        $totalResenas = $query->count();
        
        // Obtener las reseñas paginadas
        $resenas = $query->skip($offset)
                        ->take($porPagina)
                        ->get();
        
        // Preparar los datos para la respuesta
        $hayMasResenas = ($offset + $porPagina) < $totalResenas;
        
        // Transformar las reseñas para incluir la información del usuario
        $resenasFormateadas = $resenas->map(function($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('d/m/Y'),
                'user' => [
                    'name' => $review->user->name,
                    'apellido' => $review->user->apellido ?? '',
                ],
                'can_edit' => auth()->check() && auth()->id() == $review->user_id,
                'edit_url' => route('reviews.edit', $review->id)
            ];
        });
        
        return response()->json([
            'resenas' => $resenasFormateadas,
            'hay_mas' => $hayMasResenas,
            'total' => $totalResenas,
            'pagina_actual' => $pagina
        ]);
        }
    }

