<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ProfilePolicy
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
    public function view(User $user, Profile $profile): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (Auth::user()->id == $user->id)
            return true;
        else
            return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Profile $profile): bool
    {
        if (Auth::user()->id == $user->id) {
            // check if the current user is the profile owner
            if ($profile->owner->id == $user->id)
                return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Profile $profile): bool
    {
        if (Auth::user()->id == $user->id) {
            // check if the current user is the profile owner
            if ($profile->owner->id == $user->id)
                return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profile $profile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profile $profile): bool
    {
        return false;
    }
}
