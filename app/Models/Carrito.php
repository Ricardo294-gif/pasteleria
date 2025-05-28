<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';

    protected $fillable = [
        'user_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'tamano'
    ];

    /**
     * Obtiene el usuario propietario del carrito
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el producto relacionado con este item
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Obtiene otros items del mismo usuario
     */
    public function getOtherItemsAttribute()
    {
        if ($this->user_id) {
            return self::where('user_id', $this->user_id)
                ->where('id', '!=', $this->id)
                ->get();
        }

        return collect();
    }

    /**
     * Obtiene el total de todos los items del carrito del usuario
     */
    public function getUserCartTotalAttribute()
    {
        if ($this->user_id) {
            return self::where('user_id', $this->user_id)->sum('subtotal');
        }

        return $this->subtotal;
    }
}
