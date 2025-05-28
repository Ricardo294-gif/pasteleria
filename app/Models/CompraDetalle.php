<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'compra_detalles';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'compra_id',
        'producto_id',
        'nombre_producto',
        'precio_unitario',
        'cantidad',
        'subtotal',
        'tamano'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'precio_unitario' => 'float',
        'subtotal' => 'float',
    ];

    /**
     * Obtener la compra a la que pertenece este detalle.
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    /**
     * Obtener el producto asociado con este detalle.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Obtener las reseñas asociadas con este detalle de compra.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
