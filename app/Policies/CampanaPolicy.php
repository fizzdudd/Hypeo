<?php

namespace App\Policies;

use App\Models\Campana;
use App\Models\User;

/**
 * Una campaña pertenece al dueño de su producto.
 */
class CampanaPolicy
{
    public function view(User $user, Campana $campana): bool
    {
        return $this->esDueno($user, $campana);
    }

    public function update(User $user, Campana $campana): bool
    {
        return $this->esDueno($user, $campana);
    }

    public function delete(User $user, Campana $campana): bool
    {
        return $this->esDueno($user, $campana);
    }

    private function esDueno(User $user, Campana $campana): bool
    {
        return $user->id === $campana->producto->user_id;
    }
}
