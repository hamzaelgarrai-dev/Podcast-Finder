<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpisodePolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return in_array($user->role,['admin', 'animateur'])
        ?Response::allow()
        :Response::deny('only admin and podcast owner can create episode');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Episode $episode): Response
    {
        $podcast = $episode->podcast;
        return ($user->role === 'animateur' && $user->id === $podcast->user_id) || $user->role === 'admin'
        ?Response::allow()
        :Response::deny('only admin and podcast owner can update the episode');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Episode $episode): Response
    {
        $podcast = $episode->podcast;
        return ($user->role === 'animateur' && $user->id === $podcast->user_id) || $user->role === 'admin'
        ?Response::allow()
        :Response::deny('only admin and podcast owner can delete the episode');
    }

 

}
