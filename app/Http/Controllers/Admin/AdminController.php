<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Producto;
use App\Models\Compra;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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

        try {
            $totalUsuarios = User::count();
            $totalProductos = Producto::count();
            $totalCompras = Compra::count();
            
            // Cargar las ventas recientes con manejo de excepciones
            try {
                $ventasRecientes = Compra::with(['user', 'detalles.producto'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al cargar ventas recientes: ' . $e->getMessage());
                $ventasRecientes = collect(); // Colección vacía
            }

            return view('admin.dashboard', compact('totalUsuarios', 'totalProductos', 'totalCompras', 'ventasRecientes'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error en dashboard admin: ' . $e->getMessage());
            return redirect()->route('index')->with('error', 'Ha ocurrido un error al cargar el panel de administración');
        }
    }

    /**
     * Gestión de usuarios
     */
    public function usuarios()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.editar', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function actualizarUsuario(Request $request, $id)
    {
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
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente');
    }

    /**
     * Gestión de productos
     */
    public function productos()
    {
        $productos = Producto::with('categoria')
            ->select('productos.*')
            ->distinct()
            ->get();

        // Obtener todas las categorías únicas para el filtro
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Mostrar formulario para crear producto
     */
    public function crearProducto()
    {
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        return view('admin.productos.crear', compact('categorias'));
    }

    /**
     * Guardar nuevo producto
     */
    public function guardarProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcion_larga' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'ingredientes' => 'required|string',
            'categoria' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Subir la imagen a la ubicación pública
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '.' . $imagen->getClientOriginalExtension();
            // Asegúrate de que el directorio existe
            if (!file_exists(public_path('img/productos'))) {
                mkdir(public_path('img/productos'), 0755, true);
            }
            $imagen->move(public_path('img/productos'), $imagenNombre);
        } else {
            // Si no hay imagen, usar una por defecto
            $imagenNombre = 'default.jpg';

            // Verificar si existe la imagen por defecto, si no, copiarla
            if (!file_exists(public_path('img/productos/default.jpg'))) {
                // Crear el directorio si no existe
                if (!file_exists(public_path('img/productos'))) {
                    mkdir(public_path('img/productos'), 0755, true);
                }

                // Copiar una imagen por defecto si existe en storage
                if (file_exists(public_path('storage/productos/default.jpg'))) {
                    copy(public_path('storage/productos/default.jpg'), public_path('img/productos/default.jpg'));
                } elseif (file_exists(base_path('resources/img/default.jpg'))) {
                    copy(base_path('resources/img/default.jpg'), public_path('img/productos/default.jpg'));
                }
            }
        }

        // Buscar la categoría por nombre o crear una categoría ID predeterminada
        $categoria_id = 1; // Categoría por defecto
        if ($request->categoria) {
            $categoria = \App\Models\Categoria::where('nombre', $request->categoria)->first();
            if ($categoria) {
                $categoria_id = $categoria->id;
            }
        }

        $producto = \App\Models\Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'descripcion_larga' => $request->descripcion_larga,
            'precio' => $request->precio,
            'ingredientes' => $request->ingredientes,
            'imagen' => $imagenNombre,
            'categoria_id' => $categoria_id
        ]);

        // Enviar notificación a los usuarios sobre el nuevo producto
        $this->enviarNotificacionNuevoProducto($producto);

        return redirect()->route('admin.productos')->with('success', 'Producto creado correctamente');
    }

    /**
     * Enviar notificación a los usuarios sobre un nuevo producto
     */
    protected function enviarNotificacionNuevoProducto($producto)
    {
        try {
            // Importar las clases necesarias
            $userModel = new \App\Models\User();
            $notification = new \App\Notifications\NuevoProductoNotification($producto);

            // Obtener usuarios que no son administradores
            $usuarios = $userModel::where('is_admin', 0)->get();

            // Verificar si hay usuarios
            if ($usuarios->isEmpty()) {
                \Illuminate\Support\Facades\Log::warning("No se encontraron usuarios no administradores para enviar notificaciones");
                return;
            }

            // Enviar notificación a cada usuario
            \Illuminate\Support\Facades\Log::info("Enviando notificación de nuevo producto ({$producto->nombre}) a " . count($usuarios) . " usuarios");

            $fallosNotificacion = false;

            foreach ($usuarios as $usuario) {
                try {
                    // Verificar que el usuario tiene un correo
                    if (empty($usuario->email)) {
                        \Illuminate\Support\Facades\Log::warning("Usuario ID: {$usuario->id} no tiene email");
                        continue;
                    }

                    \Illuminate\Support\Facades\Log::info("Enviando notificación a {$usuario->email}");
                    $usuario->notify($notification);
                    \Illuminate\Support\Facades\Log::info("Notificación enviada a {$usuario->email}");
                } catch (\Exception $e) {
                    $fallosNotificacion = true;
                    \Illuminate\Support\Facades\Log::error("Error al enviar notificación al usuario {$usuario->id} - {$usuario->email}: " . $e->getMessage());
                }
            }

            // Si hay fallos en las notificaciones, intentar el método alternativo
            if ($fallosNotificacion) {
                \Illuminate\Support\Facades\Log::info("Intentando método alternativo de envío");
                $this->enviarCorreoNuevoProductoAlternativo($producto, $usuarios);
            }

            \Illuminate\Support\Facades\Log::info("Proceso de notificaciones completado");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error al enviar notificaciones: " . $e->getMessage());
            \Illuminate\Support\Facades\Log::error("Stack trace: " . $e->getTraceAsString());

            // Intentar método alternativo si falla completamente
            try {
                $usuarios = \App\Models\User::where('is_admin', 0)->get();
                $this->enviarCorreoNuevoProductoAlternativo($producto, $usuarios);
            } catch (\Exception $e2) {
                \Illuminate\Support\Facades\Log::error("Error al enviar correos alternativos: " . $e2->getMessage());
            }
        }
    }

    /**
     * Método alternativo de envío de correos para nuevo producto
     */
    protected function enviarCorreoNuevoProductoAlternativo($producto, $usuarios)
    {
        foreach ($usuarios as $usuario) {
            try {
                // Verificar que el usuario tiene un correo
                if (empty($usuario->email)) {
                    continue;
                }

                \Illuminate\Support\Facades\Log::info("Enviando correo alternativo a {$usuario->email}");

                \Illuminate\Support\Facades\Mail::send('emails.nuevo-producto', ['producto' => $producto], function ($message) use ($usuario, $producto) {
                    $message->to($usuario->email, $usuario->name)
                            ->subject('Nuevo producto: ' . $producto->nombre);
                });

                \Illuminate\Support\Facades\Log::info("Correo alternativo enviado a {$usuario->email}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al enviar correo alternativo a {$usuario->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Mostrar formulario para editar producto
     */
    public function editarProducto($id)
    {
        // Cargar el producto con todas sus relaciones
        $producto = Producto::findOrFail($id);
        
        // Log para depuración
        \Illuminate\Support\Facades\Log::info('Editando producto: ' . json_encode([
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'descripcion_larga' => $producto->descripcion_larga
        ]));
        
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();
        return view('admin.productos.editar', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto
     */
    public function actualizarProducto(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcion_larga' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'ingredientes' => 'required|string',
            'categoria' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->descripcion_larga = $request->descripcion_larga;
        $producto->precio = $request->precio;
        $producto->ingredientes = $request->ingredientes;

        // Buscar la categoría por nombre o crear una categoría ID predeterminada
        if ($request->categoria) {
            $categoria = \App\Models\Categoria::where('nombre', $request->categoria)->first();
            if ($categoria) {
                $producto->categoria_id = $categoria->id;
            } else {
                // Si no existe, usar ID 1 (predeterminado)
                $producto->categoria_id = 1;
            }
        }

        // Subir la imagen a la ubicación pública
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($producto->imagen && file_exists(public_path('img/productos/' . $producto->imagen))) {
                unlink(public_path('img/productos/' . $producto->imagen));
            }

            // Guardar la nueva imagen
            $imagen = $request->file('imagen');
            $imagenNombre = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('img/productos'), $imagenNombre);
            $producto->imagen = $imagenNombre;
        }

        $producto->save();

        return redirect()->route('admin.productos')->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Eliminar producto
     */
    public function eliminarProducto($id)
    {
        try {
        $producto = Producto::findOrFail($id);
        $nombreProducto = $producto->nombre;

            // Eliminar la imagen asociada si existe
            if ($producto->imagen && file_exists(public_path('img/productos/' . $producto->imagen)) && $producto->imagen != 'default.jpg') {
                unlink(public_path('img/productos/' . $producto->imagen));
            }
            
            // Eliminar referencias en otras tablas (si es necesario)
            // Esto es importante si tienes restricciones de clave foránea que impiden la eliminación
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Eliminar detalles de carrito relacionados con este producto
            \App\Models\Carrito::where('producto_id', $id)->delete();
            
            // Eliminar reseñas del producto
            \App\Models\Review::where('producto_id', $id)->delete();
            
            // Eliminar detalles de compras relacionados con este producto
            if (class_exists('\App\Models\DetalleCompra')) {
                \App\Models\DetalleCompra::where('producto_id', $id)->delete();
            }
            
            // Eliminar el producto
            $resultado = $producto->delete();
            
            // Restaurar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            if ($resultado) {
                return redirect()->route('admin.productos')
                        ->with('success', "El producto \"$nombreProducto\" ha sido eliminado correctamente");
            } else {
                return redirect()->route('admin.productos')
                        ->with('error', "No se pudo eliminar el producto \"$nombreProducto\". Por favor, inténtalo de nuevo.");
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Producto no encontrado
            return redirect()->route('admin.productos')
                    ->with('error', 'El producto que intentas eliminar no existe.');
        } catch (\Exception $e) {
            // Otros errores
            \Illuminate\Support\Facades\Log::error('Error al eliminar producto: ' . $e->getMessage());
            return redirect()->route('admin.productos')
                    ->with('error', 'No se pudo eliminar el producto. Error: ' . $e->getMessage());
        }
    }

    /**
     * Ver pedidos/compras
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
     * Ver detalles de un pedido
     */
    public function verPedido($id)
    {
        $pedido = Compra::where(function ($query) use ($id) {
                    $query->where('id', $id)
                          ->orWhere('codigo', $id);
                 })
                 ->with(['user', 'detalles.producto'])
                 ->firstOrFail();

        return view('admin.pedidos.ver', compact('pedido'));
    }

    /**
     * Actualizar estado de un pedido
     */
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
     * Enviar correo electrónico según el estado del pedido
     */
    protected function enviarCorreoEstadoPedido($pedido)
    {
        $cliente = $pedido->cliente;
        $email = $cliente ? $cliente->email : $pedido->email;
        $nombre = $cliente ? $cliente->name : $pedido->nombre;

        try {
            $asunto = '';
            $vista = '';
            $datos = ['pedido' => $pedido];

            switch ($pedido->estado) {
                case 'confirmado':
                    $asunto = 'Tu pedido ha sido aceptado - Mi Sueño Dulce';
                    $vista = 'emails.pedido_confirmado';
                    break;
                case 'en_proceso':
                    $asunto = 'Tu pedido está en elaboración - Mi Sueño Dulce';
                    $vista = 'emails.pedido_en_proceso';
                    break;
                case 'terminado':
                    $asunto = 'Tu pedido está listo para recoger - Mi Sueño Dulce';
                    $vista = 'emails.pedido_terminado';
                    break;
                case 'recogido':
                    $asunto = 'Tu pedido ha sido entregado - Mi Sueño Dulce';
                    $vista = 'emails.pedido_recogido';

                    // Cargar los detalles del pedido con los productos para el correo
                    $pedido->load(['detalles.producto']);
                    $datos = ['pedido' => $pedido];
                    break;
                case 'cancelado':
                    $asunto = 'Tu pedido ha sido cancelado - Mi Sueño Dulce';
                    $vista = 'emails.pedido_cancelado';
                    break;
                default:
                    // Si no es ninguno de estos estados, no enviar correo
                    Log::info("No se envía correo para el estado: {$pedido->estado}");
                    return;
            }

            Mail::send($vista, $datos, function ($message) use ($email, $nombre, $asunto) {
                $message->to($email, $nombre)
                        ->subject($asunto);
            });

            Log::info("Correo de estado '{$pedido->estado}' enviado a {$email}");
        } catch (\Exception $e) {
            Log::error("Error al enviar correo de estado: " . $e->getMessage());
        }
    }
}

