<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

/**
 * Privacidad: cada usuario solo ve y modifica sus propios productos.
 */
class ProductoPolicy
{
    public function view(User $user, Producto $producto): bool
    {
        return $user->id === $producto->user_id;
    }

    public function update(User $user, Producto $producto): bool
    {
        return $user->id === $producto->user_id;
    }

    public function delete(User $user, Producto $producto): bool
    {
        return $user->id === $producto->user_id;
    }
}
