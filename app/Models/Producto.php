<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'descripcion_larga',
        'precio',
        'imagen',
        'ingredientes',
        'categoria_id',
        'stock',
        'calificacion'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function carrito()
    {
        return $this->hasMany(Carrito::class);
    }

    /**
     * Obtener las reseñas del producto
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Obtener la valoración promedio del producto
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?: 0;
    }

    /**
     * Obtener el número de reseñas del producto
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}
