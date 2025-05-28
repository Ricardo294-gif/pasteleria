<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('index');
})->name('index');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::match(['get', 'post'], '/register/verify', [AuthController::class, 'solicitarVerificacion'])->name('register.verify');
Route::get('/register/confirm/{email}', [AuthController::class, 'showConfirmForm'])->name('register.confirm')->middleware('web');
Route::post('/register/confirm', [AuthController::class, 'confirmarRegistro'])->name('register.confirm.post');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/resend-code', [AuthController::class, 'reenviarCodigoVerificacion'])->name('register.resend-code');

// Rutas de recuperación de contraseña
Route::get('/recuperar-password', [AuthController::class, 'showRecuperarPasswordForm'])->name('password.request');
Route::post('/recuperar-password', [AuthController::class, 'enviarLinkRecuperacion'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Rutas de productos
Route::get('/producto/{id}', [ProductoController::class, 'detalle'])->name('producto.detalle');
Route::get('/api/productos', [ProductoController::class, 'listarProductos'])->name('api.productos');
Route::get('/api/producto/{productoId}/resenas', [ProductoController::class, 'cargarMasResenas'])->name('api.producto.resenas');

// Rutas del carrito (accesibles sin autenticación)
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::get('/carrito/count', [CarritoController::class, 'count'])->name('carrito.count');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::delete('/carrito/quitar/{id}', [CarritoController::class, 'quitar'])->name('carrito.quitar');
Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
Route::post('/carrito/incrementar/{id}', [CarritoController::class, 'incrementar'])->name('carrito.incrementar');
Route::post('/carrito/decrementar/{id}', [CarritoController::class, 'decrementar'])->name('carrito.decrementar');
Route::post('/carrito/compra-directa', [CarritoController::class, 'compraDirecta'])->name('carrito.compra.directa');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/actualizar', [AuthController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::post('/perfil/password', [AuthController::class, 'cambiarPassword'])->name('perfil.password');
    Route::get('/perfil/verificar-email', [AuthController::class, 'showVerificarEmailForm'])->name('perfil.verificar-email');
    Route::post('/perfil/verificar-email', [AuthController::class, 'verificarEmailCambio'])->name('perfil.verificar-email.post');
    Route::put('/perfil/cancelar-pedido/{id}', [AuthController::class, 'cancelarPedido'])->name('perfil.cancelar-pedido');
    Route::get('/perfil/pedido/{id}', [AuthController::class, 'verDetallePedido'])->name('perfil.pedido.detalle');
    Route::delete('/perfil/eliminar-cuenta', [AuthController::class, 'eliminarCuenta'])->name('perfil.eliminar-cuenta');

    // Ruta de compra (protegida)
Route::get('/compra', function () {
        // Obtener usuario autenticado
        $user = Auth::user();

        // Si ya hay un código enviado y no ha expirado, simplemente mostrar la vista
        // Para evitar generar un nuevo código después de enviar uno incorrecto
        if (session('verification_sent') && session('verification_code')) {
    return view('compra');
        }

        // Generar código de verificación
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Guardar datos y código en la sesión
        session([
            'nombre' => $user->name,
            'email' => $user->email,
            'telefono' => $user->telefono,
            'verification_code' => $codigo,
            'verification_sent' => true
        ]);

        // Enviar el correo con el código
        Mail::raw("Tu código de verificación es: $codigo", function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de Verificación - Mi Sueño Dulce');
        });

        return view('compra')->with('success', 'Hemos enviado un código de verificación a tu correo electrónico.');
})->name('compra');

    // Rutas de procesamiento de pago (protegidas)
Route::post('/payment/process', function (Request $request) {
    // Validar los datos del formulario
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'telefono' => 'required|string|max:20',
        'terminos' => 'required|accepted'
    ]);

        // Guardar los datos en la sesión
        session([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono']
        ]);

    // Generar código de verificación
    $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Guardar el código en la sesión
    session(['verification_code' => $codigo]);
    session(['verification_sent' => true]);

    // Enviar el correo con el código
    Mail::raw("Tu código de verificación es: $codigo", function($message) use ($validated) {
        $message->to($validated['email'])
                ->subject('Código de Verificación - Mi Sueño Dulce');
    });

    return redirect()->back()->with('success', '¡Formulario enviado correctamente! Te hemos enviado un correo con el código de verificación.');
})->name('payment.process');

Route::post('/payment/verify', function (Request $request) {
    $request->validate([
        'verification_code' => 'required|string|size:6'
    ]);

    // Verificar el código sin eliminarlo de la sesión
        if ($request->verification_code !== session('verification_code')) {
            return back()->withErrors(['verification_code' => 'El código de verificación es incorrecto.']);
        }

        // Código correcto, mostrar la página de selección de método de pago
        $user = Auth::user();

    // Comprobar si es una compra directa (almacenada en sesión)
    if (session('compra_directa')) {
        // Usar el producto de compra directa en lugar del carrito
        $producto = \App\Models\Producto::find(session('compra_directa_producto_id'));

        if ($producto) {
            $carrito = [
                (object)[
                    'producto' => $producto,
                    'producto_id' => $producto->id,
                    'cantidad' => session('compra_directa_cantidad'),
                    'precio_unitario' => session('compra_directa_precio'),
                    'subtotal' => session('compra_directa_subtotal')
                ]
            ];

            $total = session('compra_directa_subtotal');
        } else {
            // Si el producto no existe, usar el carrito normal
            $carrito = \App\Models\Carrito::where('user_id', $user->id)->with('producto')->get();
            $total = 0;
            foreach ($carrito as $item) {
                $total += $item->producto->precio * $item->cantidad;
            }
        }
    } else {
        // Usar el carrito normal
        $carrito = \App\Models\Carrito::where('user_id', $user->id)->with('producto')->get();
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item->producto->precio * $item->cantidad;
        }
        }

        return view('compra.metodo-pago', [
            'carrito' => $carrito,
            'total' => $total
        ]);
})->name('payment.verify');

Route::post('/payment/resend', function (Request $request) {
    \Illuminate\Support\Facades\Log::info('Solicitud de reenvío de código recibida', [
        'ajax' => $request->ajax(), 
        'headers' => $request->header()
    ]);
    
    // Obtener el usuario autenticado
    $user = Auth::user();
    
    if (!$user) {
        \Illuminate\Support\Facades\Log::error('No hay usuario autenticado para reenviar código');
        
        return response()->json([
            'success' => false,
            'message' => 'No se pudo autenticar el usuario'
        ], 401);
    }

    \Illuminate\Support\Facades\Log::info('Usuario autenticado: ' . $user->email);

    // Verificar si se ha enviado un código recientemente (menos de 30 segundos)
    if (session('last_code_resent') && now()->diffInSeconds(session('last_code_resent')) < 30) {
        // Si es una solicitud AJAX, devolver respuesta JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes esperar 30 segundos antes de solicitar un nuevo código.'
            ], 429); // 429 Too Many Requests
        }

        // Respuesta normal para solicitudes no-AJAX
        return back()->with('error', 'Debes esperar 30 segundos antes de solicitar un nuevo código.');
    }

    // Generar un nuevo código de verificación
    $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    \Illuminate\Support\Facades\Log::info('Nuevo código generado: ' . $codigo);

    // Actualizar el código en la sesión
    session(['verification_code' => $codigo]);
    session(['verification_sent' => true]);
    session(['last_code_resent' => now()]); // Registrar la hora del último reenvío

    // Enviar el correo con el nuevo código
    try {
        Mail::raw("Tu código de verificación es: $codigo", function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de Verificación (Reenvío) - Mi Sueño Dulce');
        });
        \Illuminate\Support\Facades\Log::info('Correo enviado a: ' . $user->email);
        
        // Si es una solicitud AJAX, devolver respuesta JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hemos enviado un nuevo código de verificación a tu correo electrónico.'
            ]);
        }

        // Respuesta normal para solicitudes no-AJAX
        return back()->with('success', 'Hemos enviado un nuevo código de verificación a tu correo electrónico.');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error al enviar correo: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar el correo: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
    }
})->name('payment.resend');

    Route::post('/payment/complete', function (Request $request) {
        // Obtener usuario y datos básicos
        $user = Auth::user();
        $userId = $user->id;

        // Registrar la solicitud actual para debugging
        \Illuminate\Support\Facades\Log::info('=== INICIANDO PROCESO DE COMPRA ===');
        \Illuminate\Support\Facades\Log::info('Usuario: ' . $user->name . ' (ID: ' . $userId . ')');
        \Illuminate\Support\Facades\Log::info('Info browser: ' . $request->input('debug_info', 'No disponible'));
        \Illuminate\Support\Facades\Log::info('Timestamp cliente: ' . $request->input('timestamp', 'No disponible'));

        // Validar el método de pago
        $request->validate([
            'metodo_pago' => 'required|string|in:tarjeta,paypal,transferencia,bizum',
        ]);

        \Illuminate\Support\Facades\Log::info('Método de pago seleccionado: ' . $request->metodo_pago);

        // Si el método es tarjeta, validar los datos adicionales
        if ($request->metodo_pago === 'tarjeta') {
            $request->validate([
                'card_number' => 'required|string',
                'expiry_date' => 'required|string',
                'cvv' => 'required|string',
                'card_holder' => 'required|string',
            ]);
            \Illuminate\Support\Facades\Log::info('Datos de tarjeta validados correctamente');
        }

        // Declarar carritoItems para usar en todo el método
        $carritoItems = null;
        $total = 0;

        // Iniciar una transacción de base de datos
        DB::beginTransaction();

        try {
            // Comprobar si es una compra directa
            if (session('compra_directa')) {
                \Illuminate\Support\Facades\Log::info('Procesando compra directa');
                // Usar el producto de compra directa
                $producto = \App\Models\Producto::find(session('compra_directa_producto_id'));

                if (!$producto) {
                    \Illuminate\Support\Facades\Log::error('Producto no encontrado: ID ' . session('compra_directa_producto_id'));
                    DB::rollBack();
                    return redirect()->route('index')->with('error', 'El producto que intentas comprar ya no está disponible.');
                }

                // Crear un array con el item para procesar
                $carritoItems = [
                    (object)[
                        'producto' => $producto,
                        'producto_id' => $producto->id,
                        'cantidad' => session('compra_directa_cantidad'),
                        'precio_unitario' => session('compra_directa_precio'),
                        'subtotal' => session('compra_directa_subtotal'),
                        'tamano' => session('compra_directa_tamano', 'normal')
                    ]
                ];

                $total = session('compra_directa_subtotal');
                \Illuminate\Support\Facades\Log::info('Compra directa - Producto: ' . $producto->nombre . ', Cantidad: ' . session('compra_directa_cantidad') . ', Total: ' . $total);
            } else {
                \Illuminate\Support\Facades\Log::info('Procesando compra desde carrito');
                // Obtener el carrito del usuario para compra normal
                $carritoItems = \App\Models\Carrito::where('user_id', $userId)->with('producto')->get();

                // Verificar que hay elementos en el carrito
                if ($carritoItems->isEmpty()) {
                    \Illuminate\Support\Facades\Log::error('Carrito vacío al intentar procesar la compra');
                    DB::rollBack();
                    return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío, no es posible procesar la compra.');
                }

                \Illuminate\Support\Facades\Log::info('Items en carrito: ' . $carritoItems->count());

                // Calcular el total
                $total = 0;
                foreach ($carritoItems as $item) {
                    $total += $item->precio_unitario * $item->cantidad;
                    \Illuminate\Support\Facades\Log::info('Item: ' . $item->producto->nombre . ', Cantidad: ' . $item->cantidad . ', Precio: ' . $item->precio_unitario . ', Subtotal: ' . ($item->precio_unitario * $item->cantidad));
                }
                \Illuminate\Support\Facades\Log::info('Total calculado: ' . $total);
            }

            // Obtener los datos del usuario de la sesión o del usuario autenticado
            $nombre = session('nombre', $user->name);
            $email = session('email', $user->email);
            $telefono = session('telefono', $user->telefono);

            // Generar código único para la compra
            $codigoCompra = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
            \Illuminate\Support\Facades\Log::info('Código de compra generado: ' . $codigoCompra);

            // Crear la compra
            $compra = \App\Models\Compra::create([
                'user_id' => $userId,
                'codigo' => $codigoCompra,
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'metodo_pago' => $request->metodo_pago,
                'estado' => 'pagado',
                'total' => $total
            ]);

            \Illuminate\Support\Facades\Log::info('Compra creada con ID: ' . $compra->id);

            // Crear los detalles de la compra
            $detallesCreados = 0;
            foreach ($carritoItems as $item) {
                \App\Models\CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $item->producto_id,
                    'nombre_producto' => $item->producto->nombre,
                    'precio_unitario' => $item->precio_unitario,
                    'cantidad' => $item->cantidad,
                    'subtotal' => $item->subtotal,
                    'tamano' => isset($item->tamano) ? $item->tamano : 'normal'
                ]);
                $detallesCreados++;
            }
            \Illuminate\Support\Facades\Log::info('Detalles de compra creados: ' . $detallesCreados);

            // IMPORTANTE: Vaciar el carrito antes de hacer redirección
            \Illuminate\Support\Facades\Log::info('Vaciando carrito para el usuario: ' . $userId);
            $itemsEliminados = \App\Models\Carrito::where('user_id', $userId)->delete();
            \Illuminate\Support\Facades\Log::info('Items eliminados del carrito: ' . $itemsEliminados);

            // Confirmar la transacción
            DB::commit();
            \Illuminate\Support\Facades\Log::info('Transacción confirmada con éxito');

            // Limpiar la sesión de compra directa si existiera
            if (session()->has('compra_directa')) {
                session()->forget([
                    'compra_directa', 'compra_directa_producto_id', 'compra_directa_cantidad',
                    'compra_directa_precio', 'compra_directa_subtotal', 'compra_directa_tamano'
                ]);
                \Illuminate\Support\Facades\Log::info('Sesión de compra directa limpiada');
            }

            // Limpiar datos de la sesión relacionados con la compra
            session()->forget([
                'nombre', 'email', 'telefono', 'verification_code', 'verification_sent'
            ]);
            \Illuminate\Support\Facades\Log::info('Datos de sesión limpiados');

            // Enviar correos de forma independiente para no bloquear la respuesta
            dispatch(function() use ($compra) {
                try {
                    \Illuminate\Support\Facades\Log::info('Enviando correo de recibo al cliente: ' . $compra->email);
                    Mail::send('emails.recibo-compra', [
                        'compra' => $compra,
                        'detalles' => $compra->detalles
                    ], function($message) use ($compra) {
                        $message->to($compra->email)
                                    ->subject('Recibo de Compra #' . $compra->codigo . ' - Mi Sueño Dulce');
                    });
                    \Illuminate\Support\Facades\Log::info('Correo de recibo enviado al cliente con éxito');
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error al enviar recibo: ' . $e->getMessage());
                }

                try {
                    $adminEmail = config('mail.admin', env('ADMIN_EMAIL', 'misuenodulceoficial@gmail.com'));
                    \Illuminate\Support\Facades\Log::info('Enviando notificación de pedido al administrador: ' . $adminEmail);
                    Mail::send('emails.notificacion-pedido', [
                        'compra' => $compra,
                        'detalles' => $compra->detalles
                    ], function($message) use ($compra, $adminEmail) {
                        $message->to($adminEmail)
                                    ->subject('Nuevo Pedido #' . $compra->codigo . ' - Mi Sueño Dulce');
                    });
                    \Illuminate\Support\Facades\Log::info('Notificación de pedido enviada al administrador con éxito');
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error al enviar notificación al admin: ' . $e->getMessage());
                }
            })->afterResponse();

            // Actualizar la marca de tiempo de compra (después de todo el proceso)
            session(['last_purchase_completed' => now()]);

            \Illuminate\Support\Facades\Log::info('=== COMPRA COMPLETADA EXITOSAMENTE ===');
            return redirect()->route('index')->with('success', '¡Tu compra ha sido procesada correctamente! Hemos enviado un recibo a tu correo electrónico.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('ERROR EN PROCESAMIENTO DE COMPRA: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());

            // Registrar estado actual
            \Illuminate\Support\Facades\Log::error('Estado de carrito: ' . \App\Models\Carrito::where('user_id', $userId)->count() . ' items');
            \Illuminate\Support\Facades\Log::error('=== FIN DE PROCESO CON ERROR ===');

            return redirect()->route('carrito.index')->with('error', 'Ha ocurrido un error al procesar tu compra. Por favor, intenta nuevamente.');
        }
    })->name('payment.complete');
});

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Rutas de administración
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('dashboard', [AdminPanelController::class, 'dashboard'])->name('dashboard');
    Route::get('productos', [AdminPanelController::class, 'productos'])->name('productos');
    Route::get('usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('pedidos', [AdminPanelController::class, 'pedidos'])->name('pedidos');

    // Productos
    Route::get('productos/crear', [AdminPanelController::class, 'crearProducto'])->name('productos.crear');
    Route::post('productos/guardar', [AdminPanelController::class, 'guardarProducto'])->name('productos.guardar');
    Route::get('productos/editar/{id}', [AdminPanelController::class, 'editarProducto'])->name('productos.editar');
    Route::put('productos/actualizar/{id}', [AdminPanelController::class, 'actualizarProducto'])->name('productos.actualizar');
    Route::delete('productos/eliminar/{id}', [AdminPanelController::class, 'eliminarProducto'])->name('productos.eliminar');

    // Usuarios
    Route::get('usuarios/crear', [UsuarioController::class, 'crear'])->name('usuarios.crear');
    Route::post('usuarios/guardar', [UsuarioController::class, 'guardar'])->name('usuarios.guardar');
    Route::get('usuarios/editar/{id}', [UsuarioController::class, 'editar'])->name('usuarios.editar');
    Route::put('usuarios/actualizar/{id}', [UsuarioController::class, 'actualizar'])->name('usuarios.actualizar');
    Route::delete('usuarios/eliminar/{id}', [UsuarioController::class, 'eliminar'])->name('usuarios.eliminar');

    // Pedidos
    Route::get('pedidos/ver/{id}', [AdminPanelController::class, 'verPedido'])->name('pedidos.ver');
    Route::put('pedidos/actualizar-estado/{id}', [AdminPanelController::class, 'actualizarEstadoPedido'])->name('pedidos.actualizar-estado');
    Route::delete('pedidos/eliminar/{id}', [AdminPanelController::class, 'eliminarPedido'])->name('pedidos.eliminar');
    
    // Reseñas
    Route::get('resenas', [App\Http\Controllers\Admin\ResenasController::class, 'index'])->name('resenas');
    Route::get('resenas/ver/{id}', [App\Http\Controllers\Admin\ResenasController::class, 'show'])->name('resenas.ver');
    Route::get('resenas/editar/{id}', [App\Http\Controllers\Admin\ResenasController::class, 'edit'])->name('resenas.editar');
    Route::put('resenas/actualizar/{id}', [App\Http\Controllers\Admin\ResenasController::class, 'update'])->name('resenas.actualizar');
    Route::put('resenas/toggle-aprobacion/{id}', [App\Http\Controllers\Admin\ResenasController::class, 'toggleApproval'])->name('resenas.toggle-aprobacion');
    Route::delete('resenas/eliminar/{id}', [App\Http\Controllers\Admin\ResenasController::class, 'destroy'])->name('resenas.eliminar');
});

// Rutas para configuración de administrador
Route::get('/admin-setup', [App\Http\Controllers\AdminSetupController::class, 'showSetupForm'])->name('admin.setup');
Route::post('/admin-setup/create', [App\Http\Controllers\AdminSetupController::class, 'createAdmin'])->name('admin.setup.create');

// Rutas de reseñas
Route::get('/productos/{id}/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::get('/productos/{id}/reviews/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create')->middleware('auth');
Route::post('/productos/{id}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::delete('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
Route::get('/productos/{id}/reviews/stats', [App\Http\Controllers\ReviewController::class, 'getStats'])->name('reviews.stats');
Route::get('/productos/{id}/reviews/user-stats', [App\Http\Controllers\ReviewController::class, 'getUserStats'])->name('reviews.user-stats')->middleware('auth');
Route::get('/reviews/{id}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit')->middleware('auth');
Route::put('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');

Route::get('/producto-test/{id}', function($id) {
    $producto = \App\Models\Producto::findOrFail($id);
    return [
        'id' => $producto->id,
        'nombre' => $producto->nombre,
        'descripcion' => $producto->descripcion,
        'descripcion_larga' => $producto->descripcion_larga,
        'timestamp' => time()
    ];
});
