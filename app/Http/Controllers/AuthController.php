<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarritoController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        Log::info('Accediendo al formulario de registro');

        // Guardar el estado del carrito antes de mostrar la página de registro
        $carritoSession = session('carrito', []);
        session(['temp_carrito' => $carritoSession]);
        Log::info('Guardando carrito temporal antes de mostrar formulario de registro: ' . count($carritoSession) . ' productos');

        // Si viene de intentar comprar, guardar una variable en la sesión
        if (request()->has('redirect_to_cart')) {
            session(['redirect_to_cart' => true]);
        }

        return view('auth.register-direct');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Guardar el carrito actual antes de crear el usuario
        $carritoAnterior = session()->get('carrito', []);

        // Si el carrito está vacío, intentar recuperar del temporal
        if (empty($carritoAnterior)) {
            $carritoTemporal = session()->get('temp_carrito', []);
            if (!empty($carritoTemporal)) {
                \Illuminate\Support\Facades\Log::info('Carrito de sesión vacío, recuperando del temporal con ' . count($carritoTemporal) . ' productos');
                $carritoAnterior = $carritoTemporal;
                session()->put('carrito', $carritoAnterior);
            }
        }

        $carritoCount = count($carritoAnterior);
        \Illuminate\Support\Facades\Log::info('Carrito antes de registrar: ' . $carritoCount . ' productos');
        \Illuminate\Support\Facades\Log::info('Contenido del carrito: ' . json_encode($carritoAnterior));

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'],
            'password' => Hash::make($validated['password']),
        ]);

        // Iniciar sesión con el nuevo usuario
        Auth::login($user);
        \Illuminate\Support\Facades\Log::info('Usuario creado y autenticado: ID ' . $user->id);

        // Verificar que el carrito aún existe en la sesión
        $carritoExiste = session()->has('carrito');
        \Illuminate\Support\Facades\Log::info('Carrito existe después de login: ' . ($carritoExiste ? 'Sí' : 'No'));

        // Restaurar el carrito si se perdió en el proceso de login
        if (!$carritoExiste && $carritoCount > 0) {
            session()->put('carrito', $carritoAnterior);
            \Illuminate\Support\Facades\Log::info('Carrito restaurado con ' . count($carritoAnterior) . ' productos');
        }

        // Intentar sincronizar el carrito con precaución
        try {
            $carritoController = app(CarritoController::class);
            $carritoController->sincronizarCarritoAlIniciarSesion();

            // Verificar si la sincronización fue exitosa
            $carritoItems = \App\Models\Carrito::where('user_id', $user->id)->count();
            \Illuminate\Support\Facades\Log::info('Productos en carrito después de sincronizar: ' . $carritoItems);

            // Si la sincronización falló, insertar directamente los productos
            if ($carritoItems == 0 && $carritoCount > 0) {
                \Illuminate\Support\Facades\Log::warning('La sincronización falló, intentando inserción directa');

                // Obtener todos los productos necesarios
                $productoIds = array_column($carritoAnterior, 'producto_id');
                $productos = \App\Models\Producto::whereIn('id', $productoIds)->get()->keyBy('id');

                foreach ($carritoAnterior as $item) {
                    $productoId = $item['producto_id'];
                    if (!isset($productos[$productoId])) continue;

                    $cantidad = $item['cantidad'];
                    $precioUnitario = $productos[$productoId]->precio;

                    \App\Models\Carrito::create([
                        'user_id' => $user->id,
                        'producto_id' => $productoId,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $precioUnitario * $cantidad
                    ]);
                }

                // Limpiar el carrito de la sesión después de la inserción manual
                session()->forget('carrito');
                \Illuminate\Support\Facades\Log::info('Carrito transferido manualmente a la base de datos');
            }

            // Limpiar datos temporales de la sesión
            session()->forget('temp_carrito');
            \Illuminate\Support\Facades\Log::info('Carrito temporal limpiado después de sincronización');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al sincronizar carrito: ' . $e->getMessage());
        }

        // Verificar la URL anterior para decidir dónde redireccionar
        $previousUrl = url()->previous();

        // Si el usuario viene de la página de un producto o del carrito, redirigir al carrito
        if (strpos($previousUrl, 'producto') !== false ||
            strpos($previousUrl, 'carrito') !== false ||
            session()->has('redirect_to_cart')) {

            // Limpiar la variable de sesión si existe
            session()->forget('redirect_to_cart');

            return redirect()->route('carrito.index');
        }

        // En otros casos, redirigir a la página principal
        return redirect()->route('index')->with('success', '¡Bienvenido/a a Mi Sueño Dulce, ' . $user->name . '! Tu cuenta ha sido creada exitosamente.');
    }

    public function showLoginForm()
    {
        // Guardar el estado del carrito antes de mostrar la página de login
        $carritoSession = session('carrito', []);
        session(['temp_carrito' => $carritoSession]);
        Log::info('Guardando carrito temporal antes de mostrar formulario de login: ' . count($carritoSession) . ' productos');

        // Si viene de intentar comprar, guardar una variable en la sesión
        if (request()->has('redirect_to_cart')) {
            session(['redirect_to_cart' => true]);
        }

        return view('auth.login-direct');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Guardar el carrito actual antes de iniciar sesión
        $carritoAnterior = session()->get('carrito', []);

        // Si el carrito está vacío, intentar recuperar del temporal
        if (empty($carritoAnterior)) {
            $carritoTemporal = session()->get('temp_carrito', []);
            if (!empty($carritoTemporal)) {
                \Illuminate\Support\Facades\Log::info('Carrito de sesión vacío, recuperando del temporal con ' . count($carritoTemporal) . ' productos');
                $carritoAnterior = $carritoTemporal;
                session()->put('carrito', $carritoAnterior);
            }
        }

        $carritoCount = count($carritoAnterior);
        \Illuminate\Support\Facades\Log::info('Carrito antes de login: ' . $carritoCount . ' productos');
        \Illuminate\Support\Facades\Log::info('Contenido del carrito: ' . json_encode($carritoAnterior));

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $userId = Auth::id();
            \Illuminate\Support\Facades\Log::info('Usuario autenticado: ID ' . $userId);

            // Verificar que el carrito aún existe en la sesión
            $carritoExiste = session()->has('carrito');
            \Illuminate\Support\Facades\Log::info('Carrito existe después de login: ' . ($carritoExiste ? 'Sí' : 'No'));

            // Restaurar el carrito si se perdió en el proceso de login
            if (!$carritoExiste && $carritoCount > 0) {
                session()->put('carrito', $carritoAnterior);
                \Illuminate\Support\Facades\Log::info('Carrito restaurado con ' . count($carritoAnterior) . ' productos');
            }

            // Intentar sincronizar el carrito con precaución
            try {
                $carritoController = app(CarritoController::class);
                $carritoController->sincronizarCarritoAlIniciarSesion();

                // Verificar si la sincronización fue exitosa
                $carritoItems = \App\Models\Carrito::where('user_id', $userId)->count();
                \Illuminate\Support\Facades\Log::info('Productos en carrito después de sincronizar: ' . $carritoItems);

                // Si la sincronización falló, insertar directamente los productos
                if ($carritoItems == 0 && $carritoCount > 0) {
                    \Illuminate\Support\Facades\Log::warning('La sincronización falló, intentando inserción directa');

                    // Obtener todos los productos necesarios
                    $productoIds = array_column($carritoAnterior, 'producto_id');
                    $productos = \App\Models\Producto::whereIn('id', $productoIds)->get()->keyBy('id');

                    foreach ($carritoAnterior as $item) {
                        $productoId = $item['producto_id'];
                        if (!isset($productos[$productoId])) continue;

                        $cantidad = $item['cantidad'];
                        $precioUnitario = $productos[$productoId]->precio;

                        \App\Models\Carrito::create([
                            'user_id' => $userId,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precioUnitario,
                            'subtotal' => $precioUnitario * $cantidad
                        ]);
                    }

                    // Limpiar el carrito de la sesión después de la inserción manual
                    session()->forget('carrito');
                    \Illuminate\Support\Facades\Log::info('Carrito transferido manualmente a la base de datos');
                }

                // Limpiar datos temporales de la sesión
                session()->forget('temp_carrito');
                \Illuminate\Support\Facades\Log::info('Carrito temporal limpiado después de sincronización');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al sincronizar carrito: ' . $e->getMessage());
            }

            // Redireccionar a los administradores al panel de administración
            if (Auth::check() && Auth::user()->is_admin == 1) {
                return redirect()->route('admin.dashboard');
            }

            // Verificar la URL anterior para decidir dónde redireccionar
            $previousUrl = url()->previous();

            // Si el usuario viene de la página de un producto o del carrito, redirigir al carrito
            if (strpos($previousUrl, 'producto') !== false ||
                strpos($previousUrl, 'carrito') !== false ||
                session()->has('redirect_to_cart')) {

                // Limpiar la variable de sesión si existe
                session()->forget('redirect_to_cart');

                return redirect()->route('carrito.index');
            }

            // En otros casos, redirigir a la página principal
            return redirect()->intended(route('index'))->with('success', '¡Bienvenido/a de nuevo, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Mostrar página de perfil de usuario
     */
    public function perfil()
    {
        $user = Auth::user();
        $compras = \App\Models\Compra::where('user_id', $user->id)
                    ->with('detalles.producto')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('auth.perfil-direct', [
            'compras' => $compras
        ]);
    }

    /**
     * Actualizar información del perfil
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'telefono' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $activeTab = $request->input('active_tab', 'info');

        // Si el email ha cambiado, iniciar el proceso de verificación
        if ($request->email !== $user->email) {
            // Generar código de verificación de 6 dígitos
            $code = sprintf('%06d', mt_rand(1, 999999));

            // Guardar en sesión los datos temporales
            session([
                'new_email' => $request->email,
                'verification_code' => $code,
                'new_name' => $request->name,
                'new_apellido' => $request->apellido,
                'new_telefono' => $request->telefono,
                'verification_expires' => now()->addMinutes(10)->timestamp
            ]);

            // Enviar correo con código de verificación
            try {
                Mail::send('emails.verificacion-cambio-email', ['code' => $code], function($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Verificación de Cambio de Correo - Mi Sueño Dulce');
                });

                return redirect()->route('perfil.verificar-email')
                                 ->with('success', 'Te hemos enviado un código de verificación a tu nuevo correo.');
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de verificación: ' . $e->getMessage());
                return redirect()->route('perfil')
                       ->with('error', 'No pudimos enviar el código de verificación. Por favor, intenta de nuevo.')
                       ->with('active_tab', $activeTab);
            }
        }

        // Actualizar directamente si el email no ha cambiado
        User::where('id', Auth::id())
            ->update([
                'name' => $request->name,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
            ]);

        return redirect()->route('perfil')
               ->with('success', 'Perfil actualizado correctamente')
               ->with('active_tab', $activeTab);
    }

    /**
     * Mostrar formulario de verificación de email
     */
    public function showVerificarEmailForm()
    {
        if (!session('new_email') || !session('verification_code')) {
            return redirect()->route('perfil')->with('error', 'No hay solicitud de cambio de correo pendiente.')->withFragment('info');
        }

        // Verificar si el código ha expirado
        if (session('verification_expires') < now()->timestamp) {
            session()->forget(['new_email', 'verification_code', 'new_name', 'new_apellido', 'new_telefono', 'verification_expires']);
            return redirect()->route('perfil')->with('error', 'El código de verificación ha expirado. Por favor, intenta de nuevo.')->withFragment('info');
        }

        return view('auth.verificar-email', [
            'new_email' => session('new_email')
        ]);
    }

    /**
     * Verificar código y cambiar email
     */
    public function verificarEmailCambio(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
            'new_email' => 'required|email'
        ]);

        // Verificar que exista una solicitud pendiente
        if (!session('new_email') || !session('verification_code')) {
            return redirect()->route('perfil')->with('error', 'No hay solicitud de cambio de correo pendiente.')->withFragment('info');
        }

        // Verificar si el código ha expirado
        if (session('verification_expires') < now()->timestamp) {
            session()->forget(['new_email', 'verification_code', 'new_name', 'new_apellido', 'new_telefono', 'verification_expires']);
            return redirect()->route('perfil')->with('error', 'El código de verificación ha expirado. Por favor, intenta de nuevo.')->withFragment('info');
        }

        // Verificar que el correo coincida con el de la sesión
        if ($request->new_email !== session('new_email')) {
            return back()->with('error', 'El correo electrónico no coincide con la solicitud original.');
        }

        // Verificar el código
        if ($request->verification_code !== session('verification_code')) {
            return back()->with('error', 'El código de verificación es incorrecto.');
        }

        // Actualizar el usuario con la nueva información
        User::where('id', Auth::id())
            ->update([
                'name' => session('new_name'),
                'apellido' => session('new_apellido'),
                'email' => session('new_email'),
                'telefono' => session('new_telefono'),
            ]);

        // Limpiar datos de la sesión
        session()->forget(['new_email', 'verification_code', 'new_name', 'new_apellido', 'new_telefono', 'verification_expires']);

        return redirect()->route('perfil')->with('success', 'Tu correo electrónico ha sido actualizado correctamente.')->withFragment('info');
    }

    /**
     * Cambiar contraseña del usuario
     */
    public function cambiarPassword(Request $request)
    {
        try {
            $messages = [
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            ];
            
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ], $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si falla la validación, redirigir de vuelta con errores preservando la pestaña activa
            return redirect()->route('perfil')
                ->withErrors($e->validator)
                ->withInput($request->only('active_tab'))
                ->with('active_tab', 'security');
        }

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            // Registrar para diagnóstico
            Log::info('Contraseña actual incorrecta para el usuario ID: ' . $user->id);
            
            // Usar redirect en lugar de back
            return redirect()->route('perfil')
                ->withErrors(['current_password' => 'La contraseña actual no es correcta'])
                ->withInput($request->only('active_tab'))
                ->with('active_tab', 'security');
        }

        User::where('id', Auth::id())
            ->update([
                'password' => Hash::make($request->password)
            ]);

        Log::info('Contraseña cambiada exitosamente para el usuario ID: ' . $user->id);

        // Redireccionar con mensaje de éxito
        return redirect()->route('perfil')
            ->with('success', 'Contraseña cambiada correctamente.')
            ->with('active_tab', 'security');
    }

    /**
     * Mostrar formulario de solicitud de recuperación de contraseña
     */
    public function showRecuperarPasswordForm()
    {
        return view('auth.recuperar-password');
    }

    /**
     * Enviar enlace de recuperación de contraseña
     */
    public function enviarLinkRecuperacion(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No se encuentra ningún usuario con ese correo electrónico.']);
        }

        // Generar token único
        $token = Str::random(60);

        // Guardar el token en la base de datos
        DB::table('password_resets')->where('email', $request->email)->delete();
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Enviar correo con enlace de recuperación
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));

        Mail::send('emails.reset-password', ['url' => $resetUrl, 'user' => $user], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Recuperación de Contraseña - Mi Sueño Dulce');
        });

        // Redireccionar a la página de inicio de sesión con mensaje de éxito
        return redirect()->route('login')->with('success', 'Se ha enviado un enlace de recuperación a tu correo electrónico.');
    }

    /**
     * Mostrar formulario para crear nueva contraseña
     */
    public function showResetPasswordForm($token)
    {
        $email = request('email');

        // Verificar si el token existe para ese email
        $tokenData = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$tokenData || !Hash::check($token, $tokenData->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Este enlace de recuperación de contraseña no es válido o ha expirado.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    /**
     * Establecer nueva contraseña
     */
    public function resetPassword(Request $request)
    {
        $messages = [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ];

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], $messages);

        // Verificar token
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Este enlace de recuperación de contraseña no es válido o ha expirado.']);
        }

        // Actualizar contraseña
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar el token usado
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Tu contraseña ha sido restablecida correctamente. Ya puedes iniciar sesión.');
    }

    /**
     * Crear usuario administrador por defecto
     * Este método se debe ejecutar desde la consola con: php artisan tinker
     * App\Http\Controllers\AuthController::crearAdmin();
     */
    public static function crearAdmin()
    {
        $admin = User::where('email', 'admin@admin.com')->first();

        if (!$admin) {
            $admin = User::create([
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'is_admin' => 1,
            ]);

            return "Usuario administrador creado correctamente";
        }

        return "El usuario administrador ya existe";
    }

    /**
     * Solicitar verificación para registro
     */
    public function verificarRegistro(Request $request)
    {
        try {
            Log::info('Solicitud de verificación recibida');
            
            // Si es una solicitud GET, mostrar el formulario de registro inicial
            if ($request->isMethod('get')) {
                return redirect()->route('register');
            }
            
            // Obtener IP del cliente para una mejor protección contra múltiples envíos
            $clientIp = $request->ip();
            
            // Comprobación por IP + sesión para prevenir múltiples envíos
            $cacheKey = 'verification_request:'.md5($clientIp.session()->getId());
            
            if (Cache::has($cacheKey)) {
                $timeLeft = Cache::get($cacheKey);
                Log::info('Intento de envío múltiple bloqueado por IP+sesión. Tiempo restante: ' . $timeLeft . ' segundos');
                
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => "Por favor espera $timeLeft segundos antes de solicitar otro código.",
                        'wait_time' => $timeLeft
                    ], 429);
                }
                
                return back()->with('error', "Por favor espera $timeLeft segundos antes de solicitar otro código.");
            }

            // Comprobar si ya se envió un correo recientemente (dentro de los últimos 30 segundos)
            $lastEmailSent = session('last_verification_email_sent');
            $now = now()->timestamp;
            
            if ($lastEmailSent && ($now - $lastEmailSent < 30)) {
                $timeLeft = 30 - ($now - $lastEmailSent);
                Log::info('Intento de envío múltiple bloqueado. Último envío hace ' . ($now - $lastEmailSent) . ' segundos');
                
                // Guardar en caché para un bloqueo más efectivo
                Cache::put($cacheKey, $timeLeft, $timeLeft);
                
                if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => "Por favor espera $timeLeft segundos antes de solicitar otro código.",
                        'wait_time' => $timeLeft
                    ], 429);
                }
                
                return back()->with('error', "Por favor espera $timeLeft segundos antes de solicitar otro código.");
            }

            // Si es un reenvío de código
            if ($request->has('resend') && $request->filled('email')) {
                // Comprobación adicional por email para reenvíos
                $emailCacheKey = 'verification_email:'.md5($request->email);
                
                if (Cache::has($emailCacheKey)) {
                    $timeLeft = Cache::get($emailCacheKey);
                    Log::info('Intento de reenvío bloqueado para: ' . $request->email . '. Tiempo restante: ' . $timeLeft . ' segundos');
                    
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                        return response()->json([
                            'success' => false,
                            'message' => "Por favor espera $timeLeft segundos antes de solicitar otro código.",
                            'wait_time' => $timeLeft
                        ], 429);
                    }
                    
                    return back()->with('error', "Por favor espera $timeLeft segundos antes de solicitar otro código.");
                }
                
                Log::info('Solicitud de reenvío de código para: ' . $request->email);

                // Verificar si existe un código para este email
                $verificationData = \App\Models\VerificationCode::where('email', $request->email)->first();

                if (!$verificationData) {
                    Log::error('No se encontró código de verificación para reenvío: ' . $request->email);

                    // Si es una solicitud AJAX, devolver JSON
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se encontró una solicitud de registro para este correo electrónico.'
                        ], 404);
                    }

                    return redirect()->route('register')
                        ->with('error', 'No se encontró una solicitud de registro para este correo electrónico.');
                }

                // Calcular segundos transcurridos
                $updatedAt = $verificationData->updated_at->getTimestamp();
                $now = now()->getTimestamp();
                $secondsElapsed = abs($now - $updatedAt); // Usar valor absoluto para evitar negativos

                Log::info('Ahora: ' . $now . ', Última actualización: ' . $updatedAt);
                Log::info('Segundos transcurridos desde la última actualización: ' . $secondsElapsed);

                // Verificar si se ha reenviado un código recientemente
                if ($secondsElapsed < 30) {
                    Log::info('Reenvío muy reciente, rechazando: ' . $request->email);
                    
                    // Calcular tiempo restante
                    $waitTime = 30 - $secondsElapsed;
                    
                    // Guardar en caché para un bloqueo más efectivo
                    Cache::put($emailCacheKey, $waitTime, $waitTime);
                    Cache::put($cacheKey, $waitTime, $waitTime);

                    // Si es una solicitud AJAX, devolver JSON
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                        return response()->json([
                            'success' => false,
                            'message' => "Por favor espera $waitTime segundos antes de solicitar otro código.",
                            'wait_time' => $waitTime
                        ], 429);
                    }

                    return back()->with('error', "Por favor espera $waitTime segundos antes de solicitar otro código.");
                }

                // Generar un nuevo código
                $code = sprintf('%06d', mt_rand(1, 999999));

                // Actualizar el código y renovar la expiración
                $verificationData->update([
                    'code' => $code,
                    'expires_at' => now()->addMinutes(10)
                ]);

                // Enviar correo con el nuevo código
                try {
                    Mail::send('emails.verification-code', [
                        'nombre' => $verificationData->name,
                        'code' => $code
                    ], function($message) use ($verificationData) {
                        $message->to($verificationData->email, $verificationData->name)
                            ->subject('Código de Verificación (Reenvío) - Mi Sueño Dulce');
                    });

                    Log::info('Código reenviado a: ' . $request->email);
                    
                    // Guardar marca de tiempo del último envío
                    session(['last_code_resent' => now()->timestamp]);
                    
                    // Guardar en caché para un bloqueo más efectivo (30 segundos)
                    Cache::put($cacheKey, 30, 30);
                    Cache::put($emailCacheKey, 30, 30);

                    // Si es una solicitud AJAX, devolver JSON
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                        return response()->json([
                            'success' => true,
                            'message' => 'Se ha enviado un nuevo código de verificación a tu correo electrónico.',
                            'code' => $code // Solo para depuración, quitar en producción
                        ]);
                    }

                    return view('auth.register-verification', [
                        'email' => $request->email,
                        'code' => $code // Solo para depuración, quitar en producción
                    ])->with('success', 'Se ha enviado un nuevo código de verificación a tu correo electrónico.');

                } catch (\Exception $e) {
                    Log::error('Error al reenviar correo: ' . $e->getMessage());

                    // Si es una solicitud AJAX, devolver JSON
                    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al enviar correo: ' . $e->getMessage()
                        ], 500);
                    }

                    return back()->with('error', 'Error al enviar correo: ' . $e->getMessage());
                }
            }

            // Proceso normal de registro
            // Bloqueo por email para evitar múltiples registros con el mismo correo
            if ($request->filled('email')) {
                $emailCacheKey = 'verification_email:'.md5($request->email);
                
                if (Cache::has($emailCacheKey)) {
                    $timeLeft = Cache::get($emailCacheKey);
                    Log::info('Intento de registro múltiple bloqueado para: ' . $request->email);
                    
                    return back()->withInput()->with('error', "Ya se envió un código a este correo. Por favor espera $timeLeft segundos o revisa tu bandeja de entrada.");
                }
            }
            
            try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'telefono' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
            ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Si falla la validación, redirigir de vuelta con errores pero preservar la entrada del usuario
                return redirect()->route('register')
                    ->withErrors($e->validator)
                    ->withInput();
            }

            Log::info('Datos validados para verificación: ' . $validated['email']);

            // Generar código de verificación de 6 dígitos
            $code = sprintf('%06d', mt_rand(1, 999999));
            Log::info('Código generado: ' . $code);

            try {
                // Guardar información temporal y código de verificación
                $verificationCode = \App\Models\VerificationCode::updateOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['name'],
                        'apellido' => $validated['apellido'],
                        'code' => $code,
                        'telefono' => $validated['telefono'] ?? '',
                        'password' => $validated['password'], // Guardar contraseña sin encriptar temporalmente
                        'expires_at' => now()->addMinutes(10), // Expira en 10 minutos
                    ]
                );

                Log::info('Código guardado en base de datos para: ' . $validated['email']);
            } catch (\Exception $e) {
                Log::error('Error al guardar el código: ' . $e->getMessage());
                return back()->with('error', 'Error al crear verificación: ' . $e->getMessage());
            }

            try {
                // Enviar correo con código de verificación
                Mail::send('emails.verification-code', [
                    'nombre' => $validated['name'],
                    'code' => $code
                ], function($message) use ($validated) {
                    $message->to($validated['email'], $validated['name'])
                        ->subject('Código de Verificación - Mi Sueño Dulce');
                });

                Log::info('Email enviado a: ' . $validated['email']);
                
                // Guardar marca de tiempo del último envío
                session(['last_verification_email_sent' => now()->timestamp]);
                
                // Guardar en caché para un bloqueo más efectivo (30 segundos)
                Cache::put($cacheKey, 30, 30);
                Cache::put("verification_email:".md5($validated['email']), 30, 30);
                
            } catch (\Exception $e) {
                Log::error('Error al enviar correo: ' . $e->getMessage());
                return back()->with('error', 'Error al enviar correo: ' . $e->getMessage());
            }

            Log::info('Renderizando vista de verificación para: ' . $validated['email']);

            // Datos importantes a pasar a la vista
            $data = [
                'email' => $validated['email'],
                'code' => $code // Solo para depuración, quitar en producción
            ];

            Log::info('Datos a pasar a la vista: ' . json_encode($data));

            // Renderizar directamente la vista sin redirección
            return view('auth.register-verification', $data)
                ->with('success', 'Se ha enviado un código de verificación a tu correo electrónico.');

        } catch (\Exception $e) {
            Log::error('Error general en verificación: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error en el proceso: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para ingresar código de verificación
     */
    public function showConfirmForm($email)
    {
        Log::info('Accediendo a la página de confirmación para: ' . $email);

        // Verificar si existe un código para este email
        $verificationData = \App\Models\VerificationCode::where('email', $email)->first();

        if (!$verificationData) {
            Log::error('No se encontró código de verificación para: ' . $email);
            return redirect()->route('register')
                ->with('error', 'No se encontró una solicitud de registro para este correo electrónico.');
        }

        if ($verificationData->expires_at->isPast()) {
            // Si el código expiró, eliminarlo
            Log::error('Código expirado para: ' . $email);
            $verificationData->delete();
            return redirect()->route('register')
                ->with('error', 'El código de verificación ha expirado. Por favor, inicia el registro nuevamente.');
        }

        Log::info('Mostrando formulario de confirmación para: ' . $email);
        return view('auth.register-verification', ['email' => $email]);
    }

    /**
     * Confirmar registro con código
     */
    public function confirmarRegistro(Request $request)
    {
        Log::info('Procesando confirmación de registro para: ' . $request->email);
        
        $validated = $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|string',
        ]);

        // Buscar verificación
        $verificationData = \App\Models\VerificationCode::where('email', $validated['email'])->first();

        if (!$verificationData) {
            Log::error('No se encontró código de verificación para: ' . $validated['email']);
            // Redirigir a la página de confirmación con el email
            return redirect()->route('register.confirm', ['email' => $validated['email']])
                ->with('error', 'No se encontró una solicitud de registro para este correo electrónico.');
        }

        if ($verificationData->expires_at->isPast()) {
            // Si el código expiró, eliminarlo
            $verificationData->delete();
            Log::error('Código expirado para: ' . $validated['email']);
            return redirect()->route('register.confirm', ['email' => $validated['email']])
                ->with('error', 'El código de verificación ha expirado. Por favor, solicita un nuevo código.');
        }

        // Verificar código
        if ($verificationData->code !== $validated['verification_code']) {
            Log::warning('Código incorrecto para: ' . $validated['email'] . '. Ingresado: ' . $validated['verification_code'] . ', Esperado: ' . $verificationData->code);
            return redirect()->route('register.confirm', ['email' => $validated['email']])
                ->with('error', 'El código que ingresaste es incorrecto.');
        }

        // Código correcto, continuar con el registro
        Log::info('Código verificado correctamente para: ' . $validated['email']);

        // Guardar carrito antes de crear el usuario
        $carritoAnterior = session()->get('carrito', []);
        $carritoCount = count($carritoAnterior);
        \Illuminate\Support\Facades\Log::info('Carrito antes de finalizar registro: ' . $carritoCount . ' productos');

        // Crear el usuario en la base de datos
        $user = User::create([
            'name' => $verificationData->name,
            'apellido' => $verificationData->apellido,
            'email' => $verificationData->email,
            'telefono' => $verificationData->telefono,
            'password' => Hash::make($verificationData->password),
        ]);

        // Eliminar la entrada de verificación
        $verificationData->delete();

        // Enviar correo de bienvenida
        try {
            Mail::send('emails.bienvenida', [
                'nombre' => $user->name,
                'apellido' => $user->apellido,
                'email' => $user->email,
                'telefono' => $user->telefono
            ], function($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('¡Bienvenido/a a Mi Sueño Dulce!');
            });

            Log::info('Correo de bienvenida enviado a: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de bienvenida: ' . $e->getMessage());
            // No interrumpimos el flujo si falla el envío del correo
        }

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Sincronizar carrito con la base de datos
        try {
            if ($carritoCount > 0) {
                \Illuminate\Support\Facades\Log::info('Sincronizando carrito después de registro verificado');

                // Restaurar el carrito si se perdió en el proceso
                if (!session()->has('carrito') && $carritoCount > 0) {
                    session()->put('carrito', $carritoAnterior);
                    \Illuminate\Support\Facades\Log::info('Carrito restaurado con ' . count($carritoAnterior) . ' productos');
                }

                $carritoController = app(CarritoController::class);
                $carritoController->sincronizarCarritoAlIniciarSesion();

                // Verificar si la sincronización fue exitosa
                $carritoItems = \App\Models\Carrito::where('user_id', $user->id)->count();
                \Illuminate\Support\Facades\Log::info('Productos en carrito después de sincronizar: ' . $carritoItems);

                // Si la sincronización falló, insertar directamente los productos
                if ($carritoItems == 0 && $carritoCount > 0) {
                    \Illuminate\Support\Facades\Log::warning('La sincronización falló, intentando inserción directa');

                    // Obtener todos los productos necesarios
                    $productoIds = array_column($carritoAnterior, 'producto_id');
                    $productos = \App\Models\Producto::whereIn('id', $productoIds)->get()->keyBy('id');

                    foreach ($carritoAnterior as $item) {
                        $productoId = $item['producto_id'];
                        if (!isset($productos[$productoId])) continue;

                        $cantidad = $item['cantidad'];
                        $precioUnitario = $productos[$productoId]->precio;

                        \App\Models\Carrito::create([
                            'user_id' => $user->id,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precioUnitario,
                            'subtotal' => $precioUnitario * $cantidad
                        ]);
                    }

                    // Limpiar el carrito de la sesión después de la inserción manual
                    session()->forget('carrito');
                    \Illuminate\Support\Facades\Log::info('Carrito transferido manualmente a la base de datos');
                }
            }

            // Limpiar datos temporales
            session()->forget('temp_carrito');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al sincronizar carrito: ' . $e->getMessage());
        }

        // Verificar si hay que redirigir al carrito
        if (session()->has('redirect_to_cart')) {
            session()->forget('redirect_to_cart');
            return redirect()->route('carrito.index')->with('success', '¡Cuenta verificada exitosamente! Ahora puedes completar tu compra.');
        }

        return redirect()->route('index')->with('success', '¡Bienvenido/a a Mi Sueño Dulce, ' . $user->name . '! Tu cuenta ha sido verificada y activada correctamente.');
    }

    /**
     * Cancelar un pedido pendiente
     */
    public function cancelarPedido(Request $request, $id)
    {
        // Buscar el pedido y verificar que pertenezca al usuario
        $pedido = \App\Models\Compra::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        // Verificar que el pedido esté en estado pagado
        if ($pedido->estado !== 'pagado') {
            return redirect()->route('perfil')
                ->with('error', 'Solo puedes cancelar pedidos que estén en estado pagado.')
                ->with('active_tab', 'pedidos');
        }

        // Actualizar el estado del pedido a cancelado
        $pedido->estado = 'cancelado';
        $pedido->save();

        // Enviar correo de cancelación al usuario
        try {
            Mail::send('emails.pedido_cancelado', ['pedido' => $pedido], function($message) use ($pedido) {
                $message->to($pedido->email)
                        ->subject('Tu pedido ha sido cancelado - Mi Sueño Dulce');
            });

            Log::info("Correo de cancelación enviado a {$pedido->email} para el pedido #{$pedido->id}");
        } catch (\Exception $e) {
            Log::error("Error al enviar correo de cancelación: " . $e->getMessage());
        }

        // Enviar notificación al administrador
        try {
            $adminEmail = config('mail.admin', env('ADMIN_EMAIL', 'misuenodulceoficial@gmail.com'));
            Mail::send('emails.admin.pedido_cancelado', ['pedido' => $pedido, 'usuario' => Auth::user()], function($message) use ($pedido, $adminEmail) {
                $message->to($adminEmail)
                        ->subject('Pedido #' . $pedido->id . ' cancelado por cliente - Mi Sueño Dulce');
            });

            Log::info("Notificación de cancelación enviada al administrador: {$adminEmail} para el pedido #{$pedido->id}");
        } catch (\Exception $e) {
            Log::error("Error al enviar notificación al administrador: " . $e->getMessage());
        }

        return redirect()->route('perfil')
            ->with('success', 'Tu pedido ha sido cancelado correctamente.')
            ->with('active_tab', 'pedidos');
    }

    /**
     * Ver detalles de un pedido específico
     */
    public function verDetallePedido($id)
    {
        Log::info('Accediendo a detalles del pedido ID/Código: ' . $id . ' para usuario: ' . Auth::id());

        try {
            // Buscar el pedido y verificar que pertenezca al usuario
            // Se busca primero por ID y luego por código si no se encuentra
            $pedido = \App\Models\Compra::where(function ($query) use ($id) {
                        $query->where('id', $id)
                              ->orWhere('codigo', $id);
                    })
                        ->where('user_id', Auth::id())
                        ->with('detalles.producto')
                        ->firstOrFail();

            Log::info('Pedido encontrado: ' . json_encode([
                'id' => $pedido->id,
                'codigo' => $pedido->codigo,
                'estado' => $pedido->estado,
                'total' => $pedido->total,
                'num_detalles' => $pedido->detalles->count()
            ]));

            // Intentar usar la vista standalone que no depende de layouts
            return view('auth.pedido-detalle-standalone', [
                'pedido' => $pedido
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar detalles del pedido: ' . $e->getMessage());
            return redirect()->route('perfil')
                ->with('error', 'No se pudo cargar el detalle del pedido. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Eliminar cuenta de usuario
     */
    public function eliminarCuenta(Request $request)
    {
        // Validar credenciales
        $request->validate([
            'password' => 'required'
        ]);

        $user = Auth::user();
        $activeTab = $request->input('active_tab', 'delete');

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->route('perfil')
                   ->withErrors(['password' => 'La contraseña ingresada es incorrecta.'])
                   ->withInput()
                   ->with('active_tab', $activeTab);
        }

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Eliminar datos relacionados
            // 1. Eliminar carrito
            Carrito::where('user_id', $user->id)->delete();

            // 2. Los pedidos y detalles se mantienen por cuestiones de registro y auditoría
            // Pero se pueden anonimizar en lugar de eliminar
            
            // 3. Eliminar cuenta
            $user->delete();

            // Confirmar cambios
            DB::commit();

            // Cerrar sesión
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            // Revertir cambios en caso de error
            DB::rollBack();
            Log::error('Error al eliminar cuenta: ' . $e->getMessage());

            return redirect()->route('perfil')
                   ->with('error', 'No pudimos eliminar tu cuenta. Por favor, intenta de nuevo más tarde.')
                   ->with('active_tab', $activeTab);
        }
    }

    /**
     * Alias para el método verificarRegistro
     * Necesario para mantener compatibilidad con las rutas existentes
     */
    public function solicitarVerificacion(Request $request)
    {
        // Si es una solicitud GET, mostrar el formulario de registro
        if ($request->isMethod('get')) {
            return redirect()->route('register');
        }
        
        // Guardar el carrito en la sesión si existe un carrito temporal
        if (session()->has('temp_carrito') && !session()->has('carrito')) {
            $tempCarrito = session()->get('temp_carrito', []);
            \Illuminate\Support\Facades\Log::info('Restaurando carrito temporal en solicitarVerificacion con ' . count($tempCarrito) . ' productos');
            session()->put('carrito', $tempCarrito);
        }

        return $this->verificarRegistro($request);
    }

    /**
     * Reenvía el código de verificación al correo electrónico durante el proceso de registro
     */
    public function reenviarCodigoVerificacion(Request $request)
    {
        try {
            Log::info('Solicitud de reenvío de código recibida');
            
            $request->validate([
                'email' => 'required|email'
            ]);
            
            // Obtener IP del cliente para protección contra múltiples envíos
            $clientIp = $request->ip();
            
            // Verificar si se ha enviado un código recientemente (menos de 30 segundos)
            $cacheKey = 'verification_request:'.md5($clientIp.session()->getId());
            
            if (Cache::has($cacheKey)) {
                $timeLeft = Cache::get($cacheKey);
                Log::info('Intento de reenvío bloqueado por IP+sesión. Tiempo restante: ' . $timeLeft . ' segundos');
                
                return response()->json([
                    'success' => false,
                    'message' => "Por favor espera $timeLeft segundos antes de solicitar otro código.",
                    'wait_time' => $timeLeft
                ], 429);
            }
            
            // Comprobar si ya se envió un correo recientemente (dentro de los últimos 30 segundos)
            $lastEmailSent = session('last_code_resent');
            $now = now()->timestamp;
            
            if ($lastEmailSent && ($now - $lastEmailSent < 30)) {
                $timeLeft = 30 - ($now - $lastEmailSent);
                Log::info('Intento de reenvío bloqueado. Último envío hace ' . ($now - $lastEmailSent) . ' segundos');
                
                // Guardar en caché para un bloqueo más efectivo
                Cache::put($cacheKey, $timeLeft, $timeLeft);
                
                return response()->json([
                    'success' => false,
                    'message' => "Por favor espera $timeLeft segundos antes de solicitar otro código.",
                    'wait_time' => $timeLeft
                ], 429);
            }
            
            // Verificar si existe un registro para este email
            $verificationData = \App\Models\VerificationCode::where('email', $request->email)->first();
            
            if (!$verificationData) {
                Log::error('No se encontró código de verificación para reenvío: ' . $request->email);
                
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró una solicitud de registro para este correo electrónico.'
                ], 404);
            }
            
            // Generar un nuevo código
            $code = sprintf('%06d', mt_rand(1, 999999));
            
            // Actualizar el código y renovar la expiración
            $verificationData->update([
                'code' => $code,
                'expires_at' => now()->addMinutes(10)
            ]);
            
            // Enviar correo con el nuevo código
            Mail::send('emails.verification-code', [
                'nombre' => $verificationData->name,
                'code' => $code
            ], function($message) use ($verificationData) {
                $message->to($verificationData->email, $verificationData->name)
                    ->subject('Código de Verificación (Reenvío) - Mi Sueño Dulce');
            });
            
            Log::info('Código reenviado a: ' . $request->email);
            
            // Guardar marca de tiempo del último envío
            session(['last_code_resent' => now()->timestamp]);
            
            // Guardar en caché para un bloqueo más efectivo (30 segundos)
            Cache::put($cacheKey, 30, 30);
            
            return response()->json([
                'success' => true,
                'message' => 'Se ha enviado un nuevo código de verificación a tu correo electrónico.',
                'code' => $code // Solo para depuración, quitar en producción
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al reenviar código: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar correo: ' . $e->getMessage()
            ], 500);
        }
    }
}
