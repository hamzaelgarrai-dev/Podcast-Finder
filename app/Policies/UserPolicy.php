<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role == "admin"
        ?Response::allow()
        :Response::deny('you dont have access to users liste');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->id == $model->id
        ?Response::allow()
        :Response::deny('you dont have access to show user details');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role === 'admin'
        ?Response::allow()
        :Response::deny('you dont have access to create a user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $user->role === 'admin'
        ?Response::allow()
        :Response::deny('you dont have access to update a user');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->role === 'admin'
        ?Response::allow()
        :Response::deny('you dont have access to delete a user');
    }


}
