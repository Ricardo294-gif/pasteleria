<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    public function index()
    {
        $items = $this->obtenerCarrito();
        $total = 0;

        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        return view('carrito.index', compact('items', 'total'));
    }

    public function count()
    {
        $items = $this->obtenerCarrito();
        // Contar productos únicos en lugar de la cantidad total
        $count = count($items);

        return response()->json(['count' => $count]);
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1|max:50',
            'tamano' => 'nullable|string|in:normal,grande,muygrande',
        ]);

        $producto = Producto::findOrFail($request->producto_id);
        
        // Asegurar que el tamaño esté definido y no sea nulo
        $tamanoSeleccionado = $request->tamano ?? 'normal';

        // Calcular precio según tamaño
        $precioUnitario = $producto->precio;
        if ($tamanoSeleccionado == 'grande') {
            $precioUnitario = $producto->precio * 1.3;
        } elseif ($tamanoSeleccionado == 'muygrande') {
            $precioUnitario = $producto->precio * 1.5;
        }

        if (Auth::check()) {
            // Usuario autenticado - guardar en BD
            $item = Carrito::where('user_id', Auth::id())
                     ->where('producto_id', $producto->id)
                     ->where('tamano', $tamanoSeleccionado)
                     ->first();

            if ($item) {
                // Verificar que la suma total no exceda 50 unidades
                if ($item->cantidad + $request->cantidad > 50) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No puedes tener más de 50 unidades de este producto en el carrito'
                        ]);
                    }
                    return redirect()->back()->with('error', 'No puedes tener más de 50 unidades de este producto en el carrito');
                }
                // Actualizar cantidad si el producto ya está en el carrito
                $item->cantidad += $request->cantidad;
                $item->subtotal = $item->precio_unitario * $item->cantidad;
                $item->save();
            } else {
                // Agregar nuevo item al carrito
                Carrito::create([
                    'user_id' => Auth::id(),
                    'producto_id' => $producto->id,
                    'cantidad' => $request->cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $precioUnitario * $request->cantidad,
                    'tamano' => $tamanoSeleccionado
                ]);
            }
        } else {
            // Usuario no autenticado - guardar en sesión
            // Obtener el carrito actual
            $carrito = $this->obtenerCarrito();

            // Comprobar si el producto ya está en el carrito con el mismo tamaño
            $encontrado = false;

            foreach ($carrito as $key => $item) {
                if ($item['producto_id'] == $producto->id && $item['tamano'] == $tamanoSeleccionado) {
                    // Verificar que la suma total no exceda 50 unidades
                    if ($carrito[$key]['cantidad'] + $request->cantidad > 50) {
                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'No puedes tener más de 50 unidades de este producto en el carrito'
                            ]);
                        }
                        return redirect()->back()->with('error', 'No puedes tener más de 50 unidades de este producto en el carrito');
                    }
                    $carrito[$key]['cantidad'] += $request->cantidad;
                    $carrito[$key]['subtotal'] = $carrito[$key]['precio_unitario'] * $carrito[$key]['cantidad'];
                    $encontrado = true;
                    break;
                }
            }

            // Si el producto no está en el carrito, añadirlo
            if (!$encontrado) {
                $carrito[] = [
                    'id' => count($carrito) + 1,
                    'producto_id' => $producto->id,
                    'cantidad' => $request->cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $precioUnitario * $request->cantidad,
                    'producto' => $producto,
                    'tamano' => $tamanoSeleccionado
                ];
            }

            // Guardar el carrito en la sesión
            $this->guardarCarrito($carrito);
        }

        // Comprobar si la solicitud espera una respuesta JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado al carrito correctamente'
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito');
    }

    public function quitar($id)
    {
        if (Auth::check()) {
            // Usuario autenticado - eliminar de BD
            Carrito::where('id', $id)
                  ->where('user_id', Auth::id())
                  ->delete();
        } else {
            // Usuario no autenticado - eliminar de sesión
            $carrito = $this->obtenerCarrito();

            foreach ($carrito as $key => $item) {
                if ($item['id'] == $id) {
                    unset($carrito[$key]);
                    break;
                }
            }

            // Reindexar el array
            $carrito = array_values($carrito);

            // Guardar el carrito en la sesión
            $this->guardarCarrito($carrito);
        }

        // Comprobar si la solicitud espera una respuesta JSON
        if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado del carrito correctamente'
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito');
    }

    public function vaciar()
    {
        if (Auth::check()) {
            // Usuario autenticado - vaciar carrito en BD
            Carrito::where('user_id', Auth::id())->delete();
        } else {
            // Usuario no autenticado - vaciar carrito en sesión
            $this->guardarCarrito([]);
        }

        // Comprobar si la solicitud espera una respuesta JSON
        if (request()->expectsJson() || request()->ajax()) {
        return response()->json([
            'success' => true,
                'message' => 'Carrito vaciado correctamente'
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado correctamente');
    }

    /**
     * Incrementar la cantidad de un producto en el carrito
     */
    public function incrementar($id)
    {
        if (Auth::check()) {
            // Usuario autenticado - incrementar en BD
            $item = Carrito::where('id', $id)
                     ->where('user_id', Auth::id())
                     ->first();

            if ($item) {
                // Verificar que no exceda el límite máximo de 50
                if ($item->cantidad >= 50) {
                    if (request()->expectsJson() || request()->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No puedes agregar más de 50 unidades de este producto'
                        ]);
                    }
                    return redirect()->route('carrito.index')->with('error', 'No puedes agregar más de 50 unidades de este producto');
                }

                $item->cantidad++;
                $item->subtotal = $item->precio_unitario * $item->cantidad;
                $item->save();

                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Cantidad incrementada',
                        'cantidad' => $item->cantidad,
                        'subtotal' => $item->subtotal
                    ]);
                }
            }
        } else {
            // Usuario no autenticado - incrementar en sesión
            $carrito = $this->obtenerCarrito();

            foreach ($carrito as $key => $item) {
                if ($item['id'] == $id) {
                    // Verificar que no exceda el límite máximo de 50
                    if ($carrito[$key]['cantidad'] >= 50) {
                        if (request()->expectsJson() || request()->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'No puedes agregar más de 50 unidades de este producto'
                            ]);
                        }
                        return redirect()->route('carrito.index')->with('error', 'No puedes agregar más de 50 unidades de este producto');
                    }

                    $carrito[$key]['cantidad']++;
                    $carrito[$key]['subtotal'] = $carrito[$key]['precio_unitario'] * $carrito[$key]['cantidad'];

                    $this->guardarCarrito($carrito);

                    if (request()->expectsJson() || request()->ajax()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Cantidad incrementada',
                            'cantidad' => $carrito[$key]['cantidad'],
                            'subtotal' => $carrito[$key]['subtotal']
                        ]);
                    }

                    break;
                }
            }
        }

        return redirect()->route('carrito.index')->with('success', 'Cantidad incrementada');
    }

    /**
     * Decrementar la cantidad de un producto en el carrito
     */
    public function decrementar($id)
    {
        $eliminar = false;

        if (Auth::check()) {
            // Usuario autenticado - decrementar en BD
            $item = Carrito::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

            if ($item) {
                if ($item->cantidad > 1) {
                    $item->cantidad--;
                    $item->subtotal = $item->precio_unitario * $item->cantidad;
                    $item->save();

                    $subtotal = $item->subtotal;
                    $cantidad = $item->cantidad;
                } else {
                    // No permitir que la cantidad baje de 1
                    if (request()->expectsJson() || request()->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La cantidad mínima es 1. Use el botón eliminar para quitar el producto'
                        ]);
                    }
                    return redirect()->route('carrito.index')->with('error', 'La cantidad mínima es 1. Use el botón eliminar para quitar el producto');
                }

                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => $eliminar ? 'Producto eliminado del carrito' : 'Cantidad decrementada',
                        'eliminado' => $eliminar,
                        'cantidad' => $eliminar ? 0 : $cantidad,
                        'subtotal' => $eliminar ? 0 : $subtotal
                    ]);
                }
            }
        } else {
            // Usuario no autenticado - decrementar en sesión
            $carrito = $this->obtenerCarrito();

            foreach ($carrito as $key => $item) {
                if ($item['id'] == $id) {
                    if ($carrito[$key]['cantidad'] > 1) {
                        $carrito[$key]['cantidad']--;
                        $carrito[$key]['subtotal'] = $carrito[$key]['precio_unitario'] * $carrito[$key]['cantidad'];
                    } else {
                        // No permitir que la cantidad baje de 1
                        if (request()->expectsJson() || request()->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'La cantidad mínima es 1. Use el botón eliminar para quitar el producto'
                            ]);
                        }
                        return redirect()->route('carrito.index')->with('error', 'La cantidad mínima es 1. Use el botón eliminar para quitar el producto');
                    }
                    break;
                }
            }

            $this->guardarCarrito($carrito);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cantidad decrementada',
                    'eliminado' => false,
                    'cantidad' => $carrito[$key]['cantidad'],
                    'subtotal' => $carrito[$key]['subtotal']
                ]);
            }
        }

        return redirect()->route('carrito.index')->with('success', 'Cantidad decrementada');
    }

    /**
     * Obtener el carrito del usuario actual
     */
    private function obtenerCarrito()
    {
        if (Auth::check()) {
            // Usuario autenticado - obtener de BD
            $items = Carrito::where('user_id', Auth::id())
                          ->with('producto')
                    ->get()
                          ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'producto_id' => $item->producto_id,
                            'cantidad' => $item->cantidad,
                            'precio_unitario' => $item->precio_unitario,
                            'subtotal' => $item->subtotal,
                            'producto' => $item->producto,
                            'tamano' => $item->tamano
                        ];
                          })
                          ->toArray();
        } else {
            // Usuario no autenticado - obtener de sesión
            $items = session('carrito', []);
        }

        return $items;
    }

    /**
     * Guardar el carrito en sesión
     */
    private function guardarCarrito($carrito)
    {
        if (Auth::check()) {
            // Usuario autenticado
            // Solo guardar los items en la base de datos sin eliminar los existentes
            foreach ($carrito as $item) {
                // Buscar si ya existe este producto en el carrito del usuario
                    $existente = Carrito::where('user_id', Auth::id())
                                 ->where('producto_id', $item['producto_id'])
                                 ->where('tamano', $item['tamano'])
                                 ->first();

                    if ($existente) {
                    // Si existe, actualizar cantidad y subtotal
                    // Verificar que no exceda el máximo de 50 unidades
                    $nuevaCantidad = $existente->cantidad + $item['cantidad'];
                    if ($nuevaCantidad > 50) {
                        $nuevaCantidad = 50;
                    }

                    $existente->cantidad = $nuevaCantidad;
                    $existente->subtotal = $existente->precio_unitario * $nuevaCantidad;
                        $existente->save();
                    } else {
                    // Si no existe, crear nuevo item
                        Carrito::create([
                            'user_id' => Auth::id(),
                            'producto_id' => $item['producto_id'],
                            'cantidad' => $item['cantidad'],
                            'precio_unitario' => $item['precio_unitario'],
                        'subtotal' => $item['subtotal'],
                        'tamano' => $item['tamano']
                    ]);
                }
            }
        } else {
            // Usuario no autenticado - guardar en sesión
            session(['carrito' => $carrito]);
        }
    }

    /**
     * Sincroniza el carrito de la sesión con la base de datos cuando el usuario inicia sesión
     */
    public function sincronizarCarritoAlIniciarSesion()
    {
        if (!Auth::check()) {
            return;
        }

        $userId = Auth::id();
        \Illuminate\Support\Facades\Log::info("Iniciando sincronización de carrito para usuario ID: $userId");

        // Obtener el carrito de la sesión
        $carritoSession = session('carrito', []);

        if (empty($carritoSession)) {
            \Illuminate\Support\Facades\Log::warning("No hay productos en el carrito de sesión para sincronizar");
            return;
        }

        \Illuminate\Support\Facades\Log::info("Carrito en sesión: " . json_encode($carritoSession));
        \Illuminate\Support\Facades\Log::info("Productos en carrito de sesión: " . count($carritoSession));

        // Recuperar los productos correspondientes a los IDs en el carrito
        $productoIds = array_column($carritoSession, 'producto_id');
        $productos = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

        \Illuminate\Support\Facades\Log::info("IDs de productos a sincronizar: " . implode(', ', $productoIds));
        \Illuminate\Support\Facades\Log::info("Productos encontrados: " . $productos->count());

        // Para cada item en el carrito de sesión, transferirlo a la base de datos
        foreach ($carritoSession as $item) {
            $productoId = $item['producto_id'];
            $cantidad = $item['cantidad'];

            if (!isset($productos[$productoId])) {
                \Illuminate\Support\Facades\Log::warning("Producto ID $productoId no encontrado en base de datos, omitiendo");
                continue;
            }

            $producto = $productos[$productoId];
            $precioUnitario = $producto->precio;
            $subtotal = $precioUnitario * $cantidad;

            \Illuminate\Support\Facades\Log::info("Procesando producto: {$producto->nombre} (ID: $productoId), cantidad: $cantidad");

            // Buscar si ya existe este producto en el carrito del usuario
            $carritoItem = Carrito::where('user_id', $userId)
                           ->where('producto_id', $productoId)
                           ->where('tamano', $item['tamano'])
                           ->first();

            if ($carritoItem) {
                // Si existe, actualizar cantidad y subtotal
                $nuevaCantidad = $carritoItem->cantidad + $cantidad;
                if ($nuevaCantidad > 50) $nuevaCantidad = 50;

                $carritoItem->cantidad = $nuevaCantidad;
                $carritoItem->subtotal = $precioUnitario * $nuevaCantidad;
                $carritoItem->save();

                \Illuminate\Support\Facades\Log::info("Producto actualizado en carrito DB: $productoId, nueva cantidad: $nuevaCantidad");
            } else {
                // Si no existe, crear nuevo item
                $nuevoItem = Carrito::create([
                    'user_id' => $userId,
                    'producto_id' => $productoId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                    'tamano' => $item['tamano']
                ]);

                \Illuminate\Support\Facades\Log::info("Nuevo producto añadido al carrito en DB: $productoId, ID del item: {$nuevoItem->id}");
            }
        }

        // Verificar que la sincronización fue exitosa
        $itemsEnDB = Carrito::where('user_id', $userId)->count();
        \Illuminate\Support\Facades\Log::info("Después de sincronizar: $itemsEnDB productos en carrito de base de datos");

        if ($itemsEnDB > 0) {
            // Solo limpiar el carrito de sesión si la sincronización fue exitosa
            session()->forget('carrito');
            \Illuminate\Support\Facades\Log::info("Carrito de sesión limpiado después de sincronización exitosa");
        } else {
            \Illuminate\Support\Facades\Log::error("¡FALLO EN SINCRONIZACIÓN! No se encontraron productos en DB después de sincronizar");
        }
    }

    /**
     * Compra directa de un producto
     *
     * Agrega el producto al carrito normal y suma la cantidad a la existente
     */
    public function compraDirecta(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1|max:50',
            'tamano' => 'nullable|string|in:normal,grande,muygrande',
        ]);

        // Obtener datos del producto
        $producto = Producto::findOrFail($request->producto_id);
        
        // Asegurar que el tamaño esté definido y no sea nulo
        $tamanoSeleccionado = $request->tamano ?? 'normal';
        
        // Calcular precio según tamaño
        $precioUnitario = $producto->precio;
        if ($tamanoSeleccionado == 'grande') {
            $precioUnitario = $producto->precio * 1.3;
        } elseif ($tamanoSeleccionado == 'muygrande') {
            $precioUnitario = $producto->precio * 1.5;
        }

        if (Auth::check()) {
            // Usuario autenticado - guardar en BD
            $item = Carrito::where('user_id', Auth::id())
                     ->where('producto_id', $producto->id)
                     ->where('tamano', $tamanoSeleccionado)
                     ->first();

            if ($item) {
                // Verificar que la suma total no exceda 50 unidades
                if ($item->cantidad + $request->cantidad > 50) {
                    return redirect()->back()->with('error', 'No puedes tener más de 50 unidades de este producto en el carrito');
                }

                // Sumar cantidad al producto ya existente en el carrito
                $item->cantidad += $request->cantidad;
                $item->subtotal = $item->precio_unitario * $item->cantidad;
                $item->save();
            } else {
                // Agregar nuevo item al carrito
                Carrito::create([
                    'user_id' => Auth::id(),
                    'producto_id' => $producto->id,
                    'cantidad' => $request->cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $precioUnitario * $request->cantidad,
                    'tamano' => $tamanoSeleccionado
                ]);
            }
        } else {
            // Usuario no autenticado - guardar en sesión
            $carrito = $this->obtenerCarrito();

            // Buscar si el producto ya existe en el carrito con el mismo tamaño
            $productoExistente = false;
            foreach ($carrito as &$item) {
                if ($item['producto_id'] == $producto->id && $item['tamano'] == $tamanoSeleccionado) {
                    // Verificar que no exceda 50 unidades
                    if ($item['cantidad'] + $request->cantidad > 50) {
                        return redirect()->back()->with('error', 'No puedes tener más de 50 unidades de este producto en el carrito');
                    }

                    // Actualizar cantidad y subtotal
                    $item['cantidad'] += $request->cantidad;
                    $item['subtotal'] = $item['precio_unitario'] * $item['cantidad'];
                    $productoExistente = true;
                    break;
                }
            }

            // Si el producto no existe en el carrito, añadirlo
            if (!$productoExistente) {
                // Generar un ID único para este item del carrito
                $itemId = count($carrito) + 1;

                $carrito[] = [
                    'id' => $itemId,
                    'producto_id' => $producto->id,
                    'cantidad' => $request->cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $precioUnitario * $request->cantidad,
                    'tamano' => $tamanoSeleccionado,
                    'producto' => $producto
                ];
            }

            // Guardar el carrito actualizado en la sesión
            $this->guardarCarrito($carrito);
        }

        // Redireccionar a la página del carrito
        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito');
    }
}
