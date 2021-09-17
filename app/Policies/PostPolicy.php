<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user, Post $post)
    {
        return $user->status === 1 && ($user->role === 2 || ($user->role === 3 && ($user->id === $post->user_id)));
    }

    public function view(User $user, Post $post)
    {
        return $user->status === 1 && ($user->role === 2 || ($user->role === 3 && ($user->id === $post->user_id)));
    }

    public function create(User $user, Post $post)
    {
        return $user->status === 1 && ($user->role === 1 || $user->role === 2 || ($user->role === 3 && $user->id === $post->user_id));
    }

    public function update(User $user, Post $post)
    {
        return $user->status === 1 && ($user->role === 3 && $user->id === $post->user_id);
    }

    public function delete(User $user, Post $post)
    {
        return $user->status === 1 && ($user->role === 3 && $user->id === $post->user_id);
    }
}
