<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
        'precio_unitario'
    ];

    /**
     * Obtiene el carrito al que pertenece este item
     */
    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }

    /**
     * Obtiene el producto relacionado con este item
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Obtiene el subtotal del item (precio * cantidad)
     */
    public function getSubtotalAttribute()
    {
        return $this->precio_unitario * $this->cantidad;
    }
}
