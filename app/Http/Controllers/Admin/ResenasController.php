<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Producto;

class ResenasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'producto'])
            ->orderBy('created_at', 'desc');
            
        // Filtros
        if ($request->has('filter')) {
            $filter = $request->filter;
            if ($filter === 'approved') {
                $query->where('is_approved', true);
            } elseif ($filter === 'pending') {
                $query->where('is_approved', false)
                      ->whereNull('rejected_at');
            } elseif ($filter === 'rejected') {
                $query->where('is_approved', false)
                      ->whereNotNull('rejected_at');
            } elseif ($filter === 'high-rating') {
                $query->where('rating', '>=', 4);
            } elseif ($filter === 'low-rating') {
                $query->where('rating', '<=', 2);
            }
        }
        
        // Búsqueda
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('producto', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            })->orWhere('comment', 'like', "%{$search}%");
        }
        
        $resenas = $query->paginate(10);
        
        return view('admin.resenas.index', [
            'resenas' => $resenas,
            'filter' => $request->filter ?? 'all',
            'search' => $request->search ?? ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resena = Review::with(['user', 'producto'])->findOrFail($id);
        return view('admin.resenas.show', compact('resena'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resena = Review::with(['user', 'producto'])->findOrFail($id);
        return view('admin.resenas.edit', compact('resena'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'comment' => 'required|min:5',
            'rating' => 'required|numeric|min:1|max:5',
            'is_approved' => 'boolean'
        ]);

        $resena = Review::findOrFail($id);
        
        $resena->update([
            'comment' => $request->comment,
            'rating' => $request->rating,
            'is_approved' => (bool)$request->is_approved
        ]);

        // Actualizar la calificación promedio del producto
        $this->updateProductRating($resena->producto_id);
        
        return redirect()->route('admin.resenas')->with('success', 'Reseña actualizada correctamente');
    }

    /**
     * Toggle the approval status of a review
     */
    public function toggleApproval(string $id)
    {
        $resena = Review::findOrFail($id);
        $resena->is_approved = !$resena->is_approved;
        $resena->save();
        
        // Actualizar la calificación promedio del producto
        $this->updateProductRating($resena->producto_id);
        
        return redirect()->back()->with('success', 
            $resena->is_approved ? 'Reseña aprobada correctamente' : 'Reseña desaprobada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resena = Review::findOrFail($id);
        $productoId = $resena->producto_id;
        
        $resena->delete();
        
        // Actualizar la calificación promedio del producto
        $this->updateProductRating($productoId);
        
        return redirect()->route('admin.resenas')->with('success', 'Reseña eliminada correctamente');
    }
    
    /**
     * Update the average rating of a product
     */
    private function updateProductRating($productoId)
    {
        $producto = Producto::findOrFail($productoId);
        $avgRating = Review::where('producto_id', $productoId)
            ->where('is_approved', true)
            ->avg('rating');
            
        $producto->calificacion = $avgRating ?? 0;
        $producto->save();
    }

    /**
     * Rechazar una reseña
     */
    public function rechazar($id)
    {
        $resena = Review::findOrFail($id);
        $resena->update([
            'is_approved' => false,
            'rejected_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'La reseña ha sido rechazada correctamente');
    }

    /**
     * Aprobar una reseña
     */
    public function aprobar($id)
    {
        $resena = Review::findOrFail($id);
        $resena->update([
            'is_approved' => true,
            'rejected_at' => null
        ]);
        
        return redirect()->back()->with('success', 'La reseña ha sido aprobada correctamente');
    }
}
