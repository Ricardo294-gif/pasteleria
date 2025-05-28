<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Muestra el listado de productos
     */
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $productos = Producto::with('categoria')->get();
        $categorias = Categoria::all();
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Mostrar formulario para crear producto
     */
    public function crear()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $categorias = Categoria::all();
        return view('admin.productos.crear', compact('categorias'));
    }

    /**
     * Guardar nuevo producto
     */
    public function guardar(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcion_larga' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'ingredientes' => 'required|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Subir imagen
        $imagen = $request->file('imagen');
        $imagenPath = $imagen->store('productos', 'public');

        // Crear producto
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'descripcion_larga' => $request->descripcion_larga,
            'precio' => $request->precio,
            'ingredientes' => $request->ingredientes,
            'categoria_id' => $request->categoria_id,
            'imagen' => $imagenPath,
        ]);

        return redirect()->route('admin.productos')->with('success', 'Producto creado correctamente');
    }

    /**
     * Mostrar formulario para editar producto
     */
    public function editar($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $producto = Producto::with('categoria')->findOrFail($id);
        $categorias = Categoria::all();
        return view('admin.productos.editar', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto
     */
    public function actualizar(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcion_larga' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'ingredientes' => 'required|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Actualizar datos del producto
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->descripcion_larga = $request->descripcion_larga;
        $producto->precio = $request->precio;
        $producto->ingredientes = $request->ingredientes;
        $producto->categoria_id = $request->categoria_id;

        // Si se subió una nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen antigua
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            // Subir nueva imagen
            $imagen = $request->file('imagen');
            $imagenPath = $imagen->store('productos', 'public');
            $producto->imagen = $imagenPath;
        }

        $producto->save();

        return redirect()->route('admin.productos')->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Eliminar producto
     */
    public function eliminar($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user() || Auth::user()->is_admin != 1) {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $producto = Producto::findOrFail($id);

        // Eliminar la imagen asociada
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos')->with('success', 'Producto eliminado correctamente');
    }
}
