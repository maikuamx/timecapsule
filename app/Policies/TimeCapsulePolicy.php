<?php

namespace App\Policies;

use App\Models\TimeCapsule;
use App\Models\User;

class TimeCapsulePolicy
{
    public function view(User $user, TimeCapsule $timeCapsule): bool
    {
        return $user->id === $timeCapsule->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TimeCapsule $timeCapsule): bool
    {
        return $user->id === $timeCapsule->user_id && !$timeCapsule->isUnlockable();
    }

    public function delete(User $user, TimeCapsule $timeCapsule): bool
    {
        return $user->id === $timeCapsule->user_id;
    }
}
