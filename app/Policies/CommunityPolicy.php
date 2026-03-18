<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;

class CommunityPolicy
{
    public function update(User $user, Community $community): bool
    {
        return $user->id === $community->user_id || $user->isAdmin();
    }

    public function delete(User $user, Community $community): bool
    {
        return $user->id === $community->user_id || $user->isAdmin();
    }
}
