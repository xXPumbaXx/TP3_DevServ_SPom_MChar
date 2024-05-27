<?php

namespace App\Policies;

use App\Models\User;

final class CreatePolicy
{

    public function create(User $user): bool
    {
        return $user->role_id == ADMIN;
    }
}
