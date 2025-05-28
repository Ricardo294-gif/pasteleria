<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'compras';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'codigo',
        'nombre',
        'email',
        'telefono',
        'metodo_pago',
        'estado',
        'total',
        'notas'
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indica qué atributos deben ser tratados como opcionales/nulos.
     *
     * @var array
     */
    protected $nullable = [
        'notas'
    ];

    /**
     * Obtener el usuario al que pertenece esta compra.
     * 
     * Si el usuario fue eliminado, retorna un modelo con valores por defecto.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuario eliminado',
            'email' => 'no-disponible@example.com',
            'is_admin' => 0
        ]);
    }

    /**
     * Obtener los detalles (productos) de esta compra.
     */
    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }
}
