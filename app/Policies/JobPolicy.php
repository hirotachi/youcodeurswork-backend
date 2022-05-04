<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->user_id || ($user->role === 'admin');
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->id === $job->user_id || ($user->role === 'admin');
    }

    public function restore(User $user, Job $job): bool
    {
        return $user->id === $job->user_id || ($user->role === 'admin');
    }

    public function forceDelete(User $user, Job $job): bool
    {
        return $user->id === $job->user_id || ($user->role === 'admin');
    }
}
