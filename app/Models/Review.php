<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'producto_id',
        'compra_detalle_id',
        'rating',
        'comment',
        'is_approved'
    ];

    /**
     * Desactivar restricciones únicas en el modelo
     */
    protected $uniqueConstraints = false;

    /**
     * Obtener el usuario que creó la reseña
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el producto asociado a la reseña
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Obtener el detalle de compra asociado a la reseña
     */
    public function compraDetalle()
    {
        return $this->belongsTo(CompraDetalle::class);
    }
}
