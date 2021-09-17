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

    public function before(User $user)
    {
        if (!$user->status) {
            return false;
        }
    }

    public function filterRoleViewAny(User $user, Post $post)
    {
        if ($user->role === 2) {
            return true;
        }
        if ($user->role === 3 && $post->user_id === $user->id) {
            return true;
        }
        return false;
    }

    public function filterRoleForm(User $user)
    {
        if ($user->role === 3) {
            return true;
        }
        return false;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Post $post)
    {
        return $user->status === 1 && ($user->role === 2 || ($user->role === 3 && ($user->id === $post->user_id)));
    }

    public function create(User $user)
    {
        return true;
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
