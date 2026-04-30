<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InstitutionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('institution.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Institution $institution): bool
    {
        if ($user->hasRole(['Admin', 'admin', 'Super Admin'])) {
            return true;
        }

        if (!$user->can('institution.view')) {
            return false;
        }

        // Check if user is linked to this specific institution
        return $user->institutions->contains($institution->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('institution.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Institution $institution): bool
    {
        if ($user->hasRole(['Admin', 'admin', 'Super Admin'])) {
            return true;
        }

        if (!$user->can('institution.edit')) {
            return false;
        }

        // Check if user is linked to this specific institution
        return $user->institutions->contains($institution->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Institution $institution): bool
    {
        if ($user->hasRole(['Admin', 'admin', 'Super Admin'])) {
            return true;
        }

        if (!$user->can('institution.delete')) {
            return false;
        }

        return $user->institutions->contains($institution->id);
    }
}
