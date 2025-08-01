<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Item $item): bool
    {
        // El dueño siempre puede ver
        if ($user && $user->id === $item->user_id) {
            return true;
        }

        // Si el objeto fue intercambiado y el usuario participó en el intercambio
        if ($item->status === 'traded' && $item->exchangeRequests()
            ->where('status', 'completed')
            ->where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id))
                ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id));
            })->exists()) {
            return true;
        }

        // Si el objeto está en match activo y el usuario es parte
        if ($item->hasMatchConfirmed() && $item->isUserInMatch($user)) {
            return true;
        }

        // Si aún está disponible (activo) y no está en match, puede verlo el público
        if ($item->status === 'active' && ! $item->hasMatchConfirmed()) {
            return true;
        }

        // Si este objeto está siendo ofrecido por el usuario autenticado
        if ($item->exchangeRequests()
            ->where('status', 'pending')
            ->whereHas('offeredItem', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->exists()) {
            return true;
        }

        // Si el usuario tiene una solicitud activa en este item
        if ($item->exchangeRequests()
            ->where('requester_id', $user->id)
            ->where('status', 'pending')
            ->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->id === $item->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->id === $item->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        return false;
    }

    /**
     * Determine whether the user can pause the model.
     */
    public function pause(User $user, Item $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function reactivate(User $user, Item $item): bool
    {
        return $user->id === $item->user_id;
    }

    /**
     * Determine whether the user can request an exchange for the model.
     */
    public function request(User $user, Item $item): bool
    {
        // No puedes solicitar tu propio objeto
        if ($user->id === $item->user_id) {
            return false;
        }

        // Solo puedes solicitar objetos activos
        if ($item->status !== 'active') {
            return false;
        }

        return true;
    }
}
