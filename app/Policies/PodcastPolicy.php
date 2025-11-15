<?php

namespace App\Policies;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PodcastPolicy
{


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return in_array($user->role,['admin', 'animateur'])
        ?Response::allow()
        :Response::deny('only admin and animateur can create a podcast');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Podcast $podcast): Response
    {
        return ($user->role === 'animateur' && $user->id === $podcast->user_id) || $user->role === 'admin'
        ?Response::allow()
        :Response::deny('only admin and podcast owner can update a podcast');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Podcast $podcast): Response
    {
        return ($user->role === 'animateur' && $user->id === $podcast->user_id) || $user->role === 'admin'
        ?Response::allow()
        :Response::deny('only admin and podcast owner can delete a podcast');
    }


}
