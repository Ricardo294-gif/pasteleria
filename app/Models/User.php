<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'telefono',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the cart items for the user.
     */
    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    public function carrito()
    {
        return $this->hasMany(Carrito::class);
    }

    /**
     * Obtener las reseñas del usuario
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Determina si el usuario es administrador.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin == 1;
    }
}
